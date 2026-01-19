<?php

namespace App\Http\Controllers;

use App\DTOs\Enrollment\CreateEnrollmentDTO;
use App\DTOs\Enrollment\UpdateEnrollmentDTO;
use App\Http\Requests\Enrollment\StoreEnrollmentRequest;
use App\Http\Requests\Enrollment\UpdateEnrollmentRequest;
use App\Models\Student;
use App\Models\CourseOffering;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function __construct(
        private EnrollmentService $enrollmentService
    ) {}

    public function index()
    {
        $enrollments = $this->enrollmentService->getAllEnrollments();
        return view('enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $students = Student::where('is_active', true)->orderBy('first_name')->get();
        
        // Solo cursos cuya fecha de inicio sea hoy o en el futuro
        $offerings = CourseOffering::with('course')
                                   ->where('is_active', true)
                                   ->whereIn('status', ['programado', 'en_curso'])
                                   ->where('start_date', '>=', now()->startOfDay())
                                   ->orderBy('start_date', 'asc')
                                   ->get();
        
        return view('enrollments.create', compact('students', 'offerings'));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            
            $dto = CreateEnrollmentDTO::fromRequest($validated);
            
            // Preparar datos del plan de pagos - SIEMPRE crear
            $totalAmount = $validated['price_paid'] - ($validated['discount'] ?? 0);
            
            $paymentPlanData = [
                'payment_type' => $validated['payment_type'] ?? 'contado',
                'total_amount' => $totalAmount,
                'periodicity' => $validated['periodicity'] ?? null,
                'number_of_installments' => $validated['number_of_installments'] ?? null,
            ];
            
            $enrollment = $this->enrollmentService->createEnrollment($dto, $paymentPlanData);

            DB::commit();

            return redirect()
                ->route('enrollments.index')
                ->with('success', 'Inscripción y plan de pagos creados exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al crear la inscripción: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $enrollment = $this->enrollmentService->getEnrollmentById($id);

        if (!$enrollment) {
            return redirect()
                ->route('enrollments.index')
                ->with('error', 'Inscripción no encontrada');
        }

        return view('enrollments.show', compact('enrollment'));
    }

    public function edit(int $id)
    {
        $enrollment = $this->enrollmentService->getEnrollmentById($id);
        $students = Student::where('is_active', true)->orderBy('first_name')->get();
        $offerings = CourseOffering::with('course')->where('is_active', true)->get();

        if (!$enrollment) {
            return redirect()
                ->route('enrollments.index')
                ->with('error', 'Inscripción no encontrada');
        }

        return view('enrollments.edit', compact('enrollment', 'students', 'offerings'));
    }

    public function update(UpdateEnrollmentRequest $request, int $id)
    {
        DB::beginTransaction();

        try {
            $dto = UpdateEnrollmentDTO::fromRequest($request->validated());
            $this->enrollmentService->updateEnrollment($id, $dto);

            DB::commit();

            return redirect()
                ->route('enrollments.show', $id)
                ->with('success', 'Inscripción actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la inscripción: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->enrollmentService->deleteEnrollment($id);

            return redirect()
                ->route('enrollments.index')
                ->with('success', 'Inscripción eliminada exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar la inscripción: ' . $e->getMessage());
        }
    }

    public function issueCertificate(int $id)
    {
        try {
            $this->enrollmentService->issueCertificate($id);

            return back()
                ->with('success', 'Certificado emitido exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al emitir certificado: ' . $e->getMessage());
        }
    }
    
    public function searchStudents(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $students = Student::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('identification', 'like', "%{$query}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$query}%"]);
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'identification', 'email', 'phone']);

        return response()->json($students);
    }

    /**
     * Muestra inscripciones pendientes de aprobación
     */
    public function pendingApprovals()
    {
        $enrollments = \App\Models\Enrollment::with([
            'student',
            'courseOffering.course',
            'courseOffering.dates'
        ])
        ->where('requires_approval', true)
        ->whereNull('management_approved')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('enrollments.pending-approvals', compact('enrollments'));
    }

    /**
     * Aprueba una inscripción
     */
    public function approve(Request $request, int $id)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $enrollment = \App\Models\Enrollment::findOrFail($id);

            if (!$enrollment->requires_approval) {
                throw new \Exception('Esta inscripción no requiere aprobación');
            }

            if ($enrollment->management_approved !== null) {
                throw new \Exception('Esta inscripción ya fue procesada');
            }

            $enrollment->management_approved = true;
            $enrollment->approved_by = auth()->id();
            $enrollment->approved_at = now();
            $enrollment->approval_notes = $request->approval_notes;
            $enrollment->save();

            DB::commit();

            return back()->with('success', 'Inscripción aprobada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al aprobar inscripción: ' . $e->getMessage());
        }
    }

    /**
     * Rechaza una inscripción
     */
    public function reject(Request $request, int $id)
    {
        $request->validate([
            'approval_notes' => 'required|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $enrollment = \App\Models\Enrollment::findOrFail($id);

            if (!$enrollment->requires_approval) {
                throw new \Exception('Esta inscripción no requiere aprobación');
            }

            if ($enrollment->management_approved !== null) {
                throw new \Exception('Esta inscripción ya fue procesada');
            }

            $enrollment->management_approved = false;
            $enrollment->approved_by = auth()->id();
            $enrollment->approved_at = now();
            $enrollment->approval_notes = $request->approval_notes;
            $enrollment->status = 'retirado'; // Cambiar estado a retirado
            $enrollment->save();

            DB::commit();

            return back()->with('success', 'Inscripción rechazada');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al rechazar inscripción: ' . $e->getMessage());
        }
    }
}