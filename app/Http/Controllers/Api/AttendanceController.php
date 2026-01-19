<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\Enrollment;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        private AttendanceService $attendanceService
    ) {}

    /**
     * Listar asistencias de una sesión
     */
    public function index(Request $request, int $sessionId): JsonResponse
    {
        $session = CourseSession::findOrFail($sessionId);
        
        $attendances = Attendance::with(['enrollment.student', 'recordedBy'])
            ->forSession($sessionId)
            ->get();

        $stats = $this->attendanceService->getSessionAttendanceStats($session);

        return response()->json([
            'session' => $session,
            'attendances' => $attendances,
            'stats' => $stats,
        ]);
    }

    /**
     * Registrar asistencia individual
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'course_session_id' => 'required|exists:course_sessions,id',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $enrollment = Enrollment::findOrFail($validated['enrollment_id']);
        $session = CourseSession::findOrFail($validated['course_session_id']);

        $attendance = $this->attendanceService->recordAttendance(
            $enrollment,
            $session,
            $validated['status'],
            'manual',
            auth()->id(),
            $validated['notes'] ?? null
        );

        return response()->json([
            'message' => 'Asistencia registrada correctamente',
            'attendance' => $attendance->load(['enrollment.student', 'courseSession']),
        ], 201);
    }

    /**
     * Registrar asistencia masiva
     */
    public function bulkStore(Request $request, int $sessionId): JsonResponse
    {
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.enrollment_id' => 'required|exists:enrollments,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.notes' => 'nullable|string',
        ]);

        $session = CourseSession::findOrFail($sessionId);

        $results = $this->attendanceService->recordBulkAttendance(
            $session,
            $validated['attendances'],
            auth()->id()
        );

        return response()->json([
            'message' => 'Asistencias registradas correctamente',
            'results' => $results,
            'total_success' => count($results['success']),
            'total_errors' => count($results['errors']),
        ]);
    }

    /**
     * Check-in mediante código QR
     */
    public function checkInQR(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
            'course_session_id' => 'required|exists:course_sessions,id',
        ]);

        $session = CourseSession::findOrFail($validated['course_session_id']);

        try {
            $attendance = $this->attendanceService->checkInWithQR(
                $validated['qr_code'],
                $session
            );

            return response()->json([
                'message' => 'Check-in exitoso',
                'attendance' => $attendance->load(['enrollment.student', 'courseSession']),
                'status' => $attendance->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en check-in: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Marcar ausencias automáticas
     */
    public function markAbsent(int $sessionId): JsonResponse
    {
        $session = CourseSession::findOrFail($sessionId);

        $count = $this->attendanceService->markAbsentStudents($session, auth()->id());

        return response()->json([
            'message' => "Se marcaron {$count} estudiantes como ausentes",
            'count' => $count,
        ]);
    }

    /**
     * Actualizar asistencia
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $attendance->notes,
            'recorded_by' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Asistencia actualizada correctamente',
            'attendance' => $attendance->fresh(['enrollment.student', 'courseSession']),
        ]);
    }

    /**
     * Obtener estadísticas de asistencia de un estudiante
     */
    public function studentStats(int $enrollmentId): JsonResponse
    {
        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($enrollmentId);
        
        $stats = $this->attendanceService->getStudentAttendanceStats($enrollment);

        return response()->json([
            'enrollment' => $enrollment,
            'stats' => $stats,
        ]);
    }

    /**
     * Obtener reporte de asistencia del curso
     */
    public function courseReport(int $courseId): JsonResponse
    {
        $report = $this->attendanceService->getCourseAttendanceReport($courseId);

        return response()->json($report);
    }

    /**
     * Generar código QR para check-in
     */
    public function generateQR(int $enrollmentId): JsonResponse
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        
        $qrCode = $this->attendanceService->generateQRCode($enrollment);

        return response()->json([
            'enrollment_id' => $enrollment->id,
            'student_name' => $enrollment->student->full_name,
            'qr_code' => $qrCode,
            'expires_in' => '24 hours',
        ]);
    }

    /**
     * Eliminar asistencia
     */
    public function destroy(int $id): JsonResponse
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'message' => 'Asistencia eliminada correctamente',
        ]);
    }
}
