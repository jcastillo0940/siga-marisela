<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\CourseOfferingDate;
use App\Models\Enrollment;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceWebController extends Controller
{
    public function __construct(
        private AttendanceService $attendanceService
    ) {}

    public function index()
    {
        $courses = Course::active()
            ->with(['activeOfferings'])
            ->get();

        return view('attendance.index', compact('courses'));
    }

    public function sessions($offeringId)
    {
        $offering = CourseOffering::with(['course', 'dates', 'enrollments.student'])
            ->findOrFail($offeringId);

        return view('attendance.sessions', compact('offering'));
    }

    public function take($sessionId)
    {
        $session = CourseOfferingDate::with(['courseOffering.course', 'courseOffering.enrollments.student', 'attendances'])
            ->findOrFail($sessionId);

        $stats = $this->attendanceService->getSessionAttendanceStats($session);

        return view('attendance.take', compact('session', 'stats'));
    }

    public function store(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.enrollment_id' => 'required|exists:enrollments,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.notes' => 'nullable|string',
        ]);

        $session = CourseOfferingDate::findOrFail($sessionId);

        $this->attendanceService->recordBulkAttendance(
            $session,
            $validated['attendances'],
            auth()->id()
        );

        return redirect()
            ->route('attendance.sessions', $session->course_offering_id)
            ->with('success', 'Asistencia registrada correctamente');
    }

    public function courseReport($offeringId)
    {
        $offering = CourseOffering::with(['course', 'enrollments.student'])
            ->findOrFail($offeringId);

        $report = $this->attendanceService->getCourseAttendanceReport($offeringId);

        return view('attendance.course-report', compact('offering', 'report'));
    }

    public function studentReport($enrollmentId)
    {
        $enrollment = Enrollment::with(['student', 'courseOffering.course', 'attendances.courseSession'])
            ->findOrFail($enrollmentId);

        $stats = $this->attendanceService->getStudentAttendanceStats($enrollment);

        return view('attendance.student-report', compact('enrollment', 'stats'));
    }
}