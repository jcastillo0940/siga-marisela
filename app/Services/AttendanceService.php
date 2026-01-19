<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\CourseOfferingDate;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Registrar asistencia de un estudiante a una sesión
     */
    public function recordAttendance(
        Enrollment $enrollment,
        CourseOfferingDate $session,
        string $status = 'present',
        string $method = 'manual',
        ?int $userId = null,
        ?string $notes = null
    ): Attendance {
        return Attendance::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'course_session_id' => $session->id,
            ],
            [
                'status' => $status,
                'checked_in_at' => in_array($status, ['present', 'late']) ? now() : null,
                'check_in_method' => $method,
                'notes' => $notes,
                'recorded_by' => $userId,
            ]
        );
    }

    /**
     * Registrar asistencia masiva para una sesión
     */
    public function recordBulkAttendance(
        CourseOfferingDate $session,
        array $attendanceData,
        ?int $userId = null
    ): array {
        $results = [
            'success' => [],
            'errors' => [],
        ];

        DB::beginTransaction();
        try {
            foreach ($attendanceData as $data) {
                $enrollment = Enrollment::find($data['enrollment_id']);
                
                if (!$enrollment) {
                    $results['errors'][] = "Inscripción {$data['enrollment_id']} no encontrada";
                    continue;
                }

                $attendance = $this->recordAttendance(
                    $enrollment,
                    $session,
                    $data['status'] ?? 'present',
                    $data['method'] ?? 'manual',
                    $userId,
                    $data['notes'] ?? null
                );

                $results['success'][] = $attendance;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    /**
     * Check-in mediante código QR
     */
    public function checkInWithQR(string $qrCode, CourseOfferingDate $session): Attendance
    {
        $parts = explode('|', $qrCode);
        
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Código QR inválido');
        }

        [$enrollmentId, $timestamp, $signature] = $parts;

        if (!$this->verifyQRSignature($enrollmentId, $timestamp, $signature)) {
            throw new \InvalidArgumentException('Firma del código QR inválida');
        }

        $enrollment = Enrollment::findOrFail($enrollmentId);

        if ($enrollment->course_offering_id !== $session->course_offering_id) {
            throw new \InvalidArgumentException('El estudiante no está inscrito en este curso');
        }

        $status = now()->gt($session->class_date->setTimeFromTimeString($session->start_time ?? '00:00')->addMinutes(15)) ? 'late' : 'present';

        return $this->recordAttendance(
            $enrollment,
            $session,
            $status,
            'qr',
            null,
            $status === 'late' ? 'Llegó tarde (QR)' : null
        );
    }

    /**
     * Marcar ausencias automáticas después de una sesión
     */
    public function markAbsentStudents(CourseOfferingDate $session, ?int $userId = null): int
    {
        $offering = $session->courseOffering;
        $enrollments = $offering->enrollments()->whereIn('status', ['inscrito', 'en_curso'])->get();
        $count = 0;

        foreach ($enrollments as $enrollment) {
            $existingAttendance = Attendance::where('enrollment_id', $enrollment->id)
                ->where('course_session_id', $session->id)
                ->exists();

            if (!$existingAttendance) {
                $this->recordAttendance(
                    $enrollment,
                    $session,
                    'absent',
                    'auto',
                    $userId,
                    'Marcado automáticamente como ausente'
                );
                $count++;
            }
        }

        return $count;
    }

    /**
     * Obtener estadísticas de asistencia de un estudiante
     */
    public function getStudentAttendanceStats(Enrollment $enrollment): array
    {
        $totalSessions = $enrollment->courseOffering->dates()->count();
        $attendances = $enrollment->attendances;

        $present = $attendances->whereIn('status', ['present', 'late'])->count();
        $absent = $attendances->where('status', 'absent')->count();
        $excused = $attendances->where('status', 'excused')->count();
        $late = $attendances->where('status', 'late')->count();

        $percentage = $totalSessions > 0 
            ? round(($present / $totalSessions) * 100, 2) 
            : 0;

        return [
            'total_sessions' => $totalSessions,
            'attended' => $present,
            'absent' => $absent,
            'excused' => $excused,
            'late' => $late,
            'pending' => $totalSessions - $attendances->count(),
            'percentage' => $percentage,
            'status' => $this->getAttendanceStatus($percentage),
        ];
    }

    /**
     * Obtener estadísticas de asistencia de una sesión
     */
    public function getSessionAttendanceStats(CourseOfferingDate $session): array
    {
        $offering = $session->courseOffering;
        $totalEnrollments = $offering->enrollments()->whereIn('status', ['inscrito', 'en_curso'])->count();
        $attendances = $session->attendances;

        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('status', 'late')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $excused = $attendances->where('status', 'excused')->count();
        $pending = $totalEnrollments - $attendances->count();

        $attendanceRate = $totalEnrollments > 0 
            ? round((($present + $late) / $totalEnrollments) * 100, 2) 
            : 0;

        return [
            'total_students' => $totalEnrollments,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'excused' => $excused,
            'pending' => $pending,
            'attendance_rate' => $attendanceRate,
        ];
    }

    /**
     * Obtener reporte de asistencia del curso
     */
    public function getCourseAttendanceReport(int $offeringId): array
    {
        $offering = \App\Models\CourseOffering::with([
            'enrollments.student',
            'enrollments.attendances'
        ])->findOrFail($offeringId);

        $sessions = $offering->dates;
        $enrollments = $offering->enrollments()->whereIn('status', ['inscrito', 'en_curso'])->get();

        $report = [];

        foreach ($enrollments as $enrollment) {
            $stats = $this->getStudentAttendanceStats($enrollment);
            
            $report[] = [
                'student_id' => $enrollment->student_id,
                'student_name' => $enrollment->student->full_name,
                'student_document' => $enrollment->student->identification,
                'enrollment_id' => $enrollment->id,
                'stats' => $stats,
                'can_generate_certificate' => $stats['percentage'] >= 80,
            ];
        }

        return [
            'course' => [
                'id' => $offering->id,
                'name' => $offering->full_name,
                'total_sessions' => $sessions->count(),
            ],
            'students' => $report,
            'summary' => [
                'total_students' => count($report),
                'eligible_for_certificate' => collect($report)->where('can_generate_certificate', true)->count(),
                'average_attendance' => collect($report)->avg('stats.percentage'),
            ],
        ];
    }

    /**
     * Determinar estado de asistencia basado en porcentaje
     */
    private function getAttendanceStatus(float $percentage): string
    {
        if ($percentage >= 90) return 'excellent';
        if ($percentage >= 80) return 'good';
        if ($percentage >= 70) return 'regular';
        return 'poor';
    }

    /**
     * Verificar firma de código QR
     */
    private function verifyQRSignature(string $enrollmentId, string $timestamp, string $signature): bool
    {
        $secret = config('app.key');
        $expectedSignature = hash_hmac('sha256', $enrollmentId . $timestamp, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Generar código QR para check-in
     */
    public function generateQRCode(Enrollment $enrollment): string
    {
        $timestamp = now()->timestamp;
        $secret = config('app.key');
        $signature = hash_hmac('sha256', $enrollment->id . $timestamp, $secret);
        
        return implode('|', [$enrollment->id, $timestamp, $signature]);
    }
}