<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseOffering;
use App\Services\LeadService;
use App\DTOs\Lead\CreateLeadDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PublicLeadController extends Controller
{
    public function __construct(
        private readonly LeadService $leadService
    ) {}

    /**
     * Muestra el formulario público de registro.
     */
    public function create()
    {
        $courses = Course::where('is_active', true)
            ->whereHas('offerings', function($q) {
                $q->where('is_active', true)
                  ->whereIn('status', ['programado', 'en_curso'])
                  // Verificar que la PRIMERA clase no cancelada sea en el futuro
                  ->whereRaw('(SELECT MIN(class_date) FROM course_offering_dates WHERE course_offering_id = course_offerings.id AND is_cancelled = 0) > ?', [now()->toDateString()])
                  ->whereRaw('max_students > (SELECT COUNT(*) FROM enrollments WHERE course_offering_id = course_offerings.id AND status IN ("inscrito", "en_curso"))');
            })->get();

        return view('public.leads.register', compact('courses'));
    }

    /**
     * Retorna las ofertas para un curso específico.
     * Ahora incluye si tiene reglas de precios activas.
     */
    public function getOfferings(Course $course)
    {
        try {
            $offerings = $course->offerings()
                ->with(['dates', 'pricingRules']) // Cargar reglas de precios
                ->where('is_active', true)
                ->whereIn('status', ['programado', 'en_curso'])
                // Verificar que la PRIMERA clase no cancelada sea en el futuro
                ->whereRaw('(SELECT MIN(class_date) FROM course_offering_dates WHERE course_offering_id = course_offerings.id AND is_cancelled = 0) > ?', [now()->toDateString()])
                ->whereRaw('max_students > (SELECT COUNT(*) FROM enrollments WHERE course_offering_id = course_offerings.id AND status IN ("inscrito", "en_curso"))')
                ->get()
                ->filter(function ($offering) {
                    return $offering->available_spots > 0;
                })
                ->map(function ($offering) {
                    // Verificar si tiene reglas activas para grupos (mínimo 2 personas)
                    $hasRules = $offering->pricingRules->where('is_active', true)
                        ->where('min_students', '>=', 2)
                        ->isNotEmpty();

                    return [
                        'id' => $offering->id,
                        'display' => $offering->public_schedule_label,
                        'has_pricing_rules' => $hasRules // <--- Nueva bandera crítica
                    ];
                })->values();

            return response()->json($offerings);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Calcula el precio total y descuentos según reglas
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'student_count' => 'required|integer|min:1',
        ]);

        $offering = CourseOffering::with('pricingRules')->find($request->course_offering_id);
        $count = $request->student_count;
        $originalPrice = $offering->price;
        
        // Buscar la mejor regla aplicable
        $rule = $offering->getBestPricingRule($count);
        
        if ($rule) {
            $pricePerStudent = $rule->calculatePricePerStudent($originalPrice, $count);
            $total = $pricePerStudent * $count;
            $appliedRuleName = $rule->name ?: 'Descuento por grupo';
        } else {
            $pricePerStudent = $originalPrice;
            $total = $originalPrice * $count;
            $appliedRuleName = null;
        }

        return response()->json([
            'original_price' => $originalPrice,
            'price_per_student' => $pricePerStudent,
            'total' => $total,
            'applied_rule' => $appliedRuleName,
            'savings' => ($originalPrice * $count) - $total
        ]);
    }

    /**
     * Guarda el lead (o los leads grupales)
     */
    public function store(Request $request)
    {
        // 1. Validación ampliada para soportar compañeros
        $validated = $request->validate([
            // Datos del estudiante principal
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'course_offering_id' => 'required|exists:course_offerings,id',
            'student_photo' => 'nullable|image|max:2048',
            'who_fills_form' => 'nullable|in:Alumna,Madre/Padre,Tutor',
            'age' => 'required|numeric',
            'birth_date_text' => 'required|date',
            'address_full' => 'required|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string',
            'parent_relationship' => 'nullable|string',
            'parent_occupation' => 'nullable|string',
            'occupation' => 'required|string',
            'social_media_handle' => 'nullable|string',
            'medical_notes_lead' => 'nullable|string',
            
            // Datos comunes
            'payment_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string',
            
            // Datos de compañeros (array)
            'partners' => 'nullable|array',
            'partners.*.first_name' => 'required|string|max:255',
            'partners.*.last_name' => 'required|string|max:255',
            'partners.*.email' => 'required|email',
            'partners.*.phone' => 'nullable|string|max:20',
            'partners.*.age' => 'required|numeric',
            'partners.*.birth_date_text' => 'required|date',
            'partners.*.who_fills_form' => 'nullable|in:Alumna,Madre/Padre,Tutor',
        ]);

        // 2. Validar que el curso tenga fechas futuras y cupos disponibles
        $offering = CourseOffering::with(['dates', 'pricingRules'])->find($validated['course_offering_id']);

        if (!$offering) {
            return back()->withInput()->with('error', 'La programación seleccionada no existe.');
        }

        // Verificar que la PRIMERA clase no cancelada sea en el futuro (curso no ha empezado)
        $firstClassDate = $offering->dates()
            ->where('is_cancelled', false)
            ->orderBy('class_date')
            ->first();

        if (!$firstClassDate || $firstClassDate->class_date <= now()->toDateString()) {
            return back()->withInput()->with('error', 'Este curso ya inició o no tiene clases programadas. Por favor selecciona otro curso.');
        }

        // Calcular total de estudiantes (1 principal + N compañeros)
        $partners = $request->input('partners', []);
        $totalStudents = 1 + count($partners);

        // Verificar cupos disponibles
        if ($offering->available_spots < $totalStudents) {
            return back()->withInput()->with('error', "Solo quedan {$offering->available_spots} cupos disponibles en este curso.");
        }

        // Si no se proporciona who_fills_form (persona mayor de edad), establecer valor por defecto
        if (empty($validated['who_fills_form'])) {
            $validated['who_fills_form'] = 'Alumna';
        }

        // =========================================================================
        // VALIDACIÓN DE DUPLICADOS (CORREGIDA)
        // =========================================================================
        // 1. Buscamos si el estudiante ya existe en el sistema
        $existingStudent = \App\Models\Student::where('email', $validated['email'])->first();

        if ($existingStudent) {
            // 2. Verificamos inscripción SOLO para este 'course_offering_id' específico
            $alreadyEnrolled = \App\Models\Enrollment::where('student_id', $existingStudent->id)
                ->where('course_offering_id', $validated['course_offering_id'])
                ->whereIn('status', ['inscrito', 'en_curso', 'completado'])
                ->exists();

            if ($alreadyEnrolled) {
                return back()
                    ->withInput()
                    ->with('error', 'Ya tienes una inscripción activa o completada para esta fecha/generación específica. Puedes registrarte en otros cursos, pero no repetir el mismo.');
            }
        }

        // 3. Opcional: Evitar duplicar LEADS pendientes
        $pendingLead = \App\Models\Lead::where('email', $validated['email'])
            ->where('course_offering_id', $validated['course_offering_id'])
            ->whereIn('status', ['nuevo', 'contactado', 'en_proceso'])
            ->exists();

        if ($pendingLead) {
            return back()
                ->withInput()
                ->with('error', 'Ya recibimos tu solicitud para este curso y la estamos procesando. No es necesario enviarla de nuevo.');
        }
        // =========================================================================

        // Calcular precio real a aplicar según reglas de grupo
        $rule = $offering->getBestPricingRule($totalStudents);
        $pricePerStudent = $rule ? $rule->calculatePricePerStudent($offering->price, $totalStudents) : $offering->price;

        DB::beginTransaction();

        try {
            // Gestión de Archivos
            $photoPath = $request->hasFile('student_photo') 
                ? $request->file('student_photo')->store('leads/photos', 'public') 
                : null;

            $receiptPath = $request->hasFile('payment_receipt') 
                ? $request->file('payment_receipt')->store('leads/payments', 'public') 
                : null;
            
            // Generar código de grupo único (solo si hay compañeros)
            $groupCode = count($partners) > 0 ? 'GRP-' . strtoupper(uniqid()) : null;

            // --- 1. Crear Lead Principal ---
            $dto = new CreateLeadDTO(
                first_name: $validated['first_name'],
                last_name: $validated['last_name'],
                email: $validated['email'],
                phone: $validated['phone'],
                source: count($partners) > 0 ? 'web_group' : 'web',
                status: 'nuevo',
                student_photo: $photoPath,
                who_fills_form: $validated['who_fills_form'],
                age: (string)$validated['age'],
                birth_date_text: $validated['birth_date_text'],
                address_full: $validated['address_full'],
                parent_name: $validated['parent_name'] ?? null,
                parent_phone: $validated['parent_phone'] ?? null,
                parent_relationship: $validated['parent_relationship'] ?? null,
                parent_occupation: $validated['parent_occupation'] ?? null,
                occupation: $validated['occupation'],
                social_media_handle: $validated['social_media_handle'] ?? null,
                medical_notes_lead: $validated['medical_notes_lead'] ?? null,
                payment_receipt_path: $receiptPath,
                payment_status: 'pending',
                course_offering_id: $validated['course_offering_id']
            );

            $mainLead = $this->leadService->createLead($dto);

            // Actualizar campos específicos de grupo
            if ($groupCode) {
                $mainLead->update([
                    'group_code' => $groupCode,
                    'notes' => "Líder del grupo. Inscripción de {$totalStudents} personas. Precio acordado: \${$pricePerStudent} c/u. " . ($validated['notes'] ?? ''),
                ]);
            }

            // --- 2. Crear Leads de Compañeros ---
            foreach ($partners as $index => $partnerData) {
                $partnerDto = new CreateLeadDTO(
                    first_name: $partnerData['first_name'],
                    last_name: $partnerData['last_name'],
                    email: $partnerData['email'],
                    phone: $partnerData['phone'] ?? $validated['phone'],
                    source: 'web_group',
                    status: 'nuevo',
                    student_photo: null,
                    who_fills_form: $partnerData['who_fills_form'] ?? 'Alumna',
                    age: (string)$partnerData['age'],
                    birth_date_text: $partnerData['birth_date_text'],
                    address_full: $validated['address_full'],
                    parent_name: null,
                    parent_phone: null,
                    parent_relationship: null,
                    parent_occupation: null,
                    occupation: 'Estudiante',
                    social_media_handle: null,
                    medical_notes_lead: null,
                    payment_receipt_path: $receiptPath,
                    payment_status: 'pending',
                    course_offering_id: $validated['course_offering_id']
                );

                $partnerLead = $this->leadService->createLead($partnerDto);

                $partnerLead->update([
                    'group_code' => $groupCode,
                    'notes' => "Compañero de grupo de {$validated['first_name']} {$validated['last_name']}. Precio acordado: \${$pricePerStudent} c/u.",
                ]);
            }

            DB::commit();

            $message = count($partners) > 0 
                ? "¡Inscripción grupal recibida! Hemos registrado a las {$totalStudents} personas correctamente."
                : 'Tu registro y comprobante han sido recibidos. Validaremos tu pago en breve.';

            return redirect()->route('public.register.success')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Limpieza de archivos si falla la DB
            if (isset($photoPath)) Storage::disk('public')->delete($photoPath);
            if (isset($receiptPath)) Storage::disk('public')->delete($receiptPath);

            return back()->withInput()->with('error', 'Hubo un error al procesar tu inscripción: ' . $e->getMessage());
        }
    }
}