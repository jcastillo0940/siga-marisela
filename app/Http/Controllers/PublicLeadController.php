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
                  ->whereHas('dates', function($dateQuery) {
                      $dateQuery->where('class_date', '>', now()->toDateString())
                                ->where('is_cancelled', false);
                  })
                  ->whereRaw('max_students > (SELECT COUNT(*) FROM enrollments WHERE course_offering_id = course_offerings.id AND status IN ("inscrito", "en_curso"))');
            })->get();

        return view('public.leads.register', compact('courses'));
    }

    /**
     * Retorna las ofertas para un curso específico.
     * Utiliza el Accessor "public_schedule_label" definido en el modelo CourseOffering.
     */
    public function getOfferings(Course $course)
    {
        try {
            $offerings = $course->offerings()
                ->with(['dates'])
                ->where('is_active', true)
                ->whereIn('status', ['programado', 'en_curso'])
                ->whereHas('dates', function($dateQuery) {
                    $dateQuery->where('class_date', '>', now()->toDateString())
                              ->where('is_cancelled', false);
                })
                ->whereRaw('max_students > (SELECT COUNT(*) FROM enrollments WHERE course_offering_id = course_offerings.id AND status IN ("inscrito", "en_curso"))')
                ->get()
                ->filter(function ($offering) {
                    return $offering->available_spots > 0;
                })
                ->map(function ($offering) {
                    return [
                        'id' => $offering->id,
                        'display' => $offering->public_schedule_label
                    ];
                });

            return response()->json($offerings);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Procesa el envío del formulario.
     */
    public function store(Request $request)
    {
        // 1. Validación: Se quitó el "unique:leads,email" para permitir múltiples intereses.
        $validated = $request->validate([
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
            'payment_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Si no se proporciona who_fills_form (persona mayor de edad), establecer valor por defecto
        if (empty($validated['who_fills_form'])) {
            $validated['who_fills_form'] = 'Alumna';
        }

        DB::beginTransaction();

        try {
            // 2. Gestión de Archivos
            $photoPath = $request->hasFile('student_photo') 
                ? $request->file('student_photo')->store('leads/photos', 'public') 
                : null;

            $receiptPath = $request->hasFile('payment_receipt') 
                ? $request->file('payment_receipt')->store('leads/payments', 'public') 
                : null;

            // 3. Creación del DTO para el servicio
            $dto = new CreateLeadDTO(
                first_name: $validated['first_name'],
                last_name: $validated['last_name'],
                email: $validated['email'],
                phone: $validated['phone'],
                source: 'web',
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

            // 4. Registro a través del Servicio
            $this->leadService->createLead($dto);

            DB::commit();

            return redirect()->route('public.register.success')
                ->with('success', 'Tu registro y comprobante han sido recibidos. Validaremos tu pago en breve.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Limpieza de archivos si falla la DB
            if (isset($photoPath)) Storage::disk('public')->delete($photoPath);
            if (isset($receiptPath)) Storage::disk('public')->delete($receiptPath);

            return back()->withInput()->with('error', 'Hubo un error al procesar tu inscripción: ' . $e->getMessage());
        }
    }
}