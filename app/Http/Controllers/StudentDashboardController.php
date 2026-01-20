<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Certificate;
use App\Models\CourseMaterial;
use App\Models\MealMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index($studentId = null)
    {
        // Si no se proporciona studentId, usar el estudiante autenticado
        // o el primer estudiante (esto se puede mejorar con autenticación de estudiantes)
        if ($studentId) {
            $student = Student::findOrFail($studentId);
        } else {
            // Por ahora, redireccionar a la selección de estudiante
            return redirect()->route('student-dashboard.select');
        }

        // Cargar inscripciones activas
        $activeEnrollments = Enrollment::with(['courseOffering.course', 'courseOffering.sessions', 'paymentPlan.schedules'])
            ->where('student_id', $student->id)
            ->whereIn('status', ['active', 'pending'])
            ->get();

        // Cargar historial de pagos
        $payments = Payment::with('enrollment.courseOffering.course')
            ->whereHas('enrollment', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->orderBy('payment_date', 'desc')
            ->take(10)
            ->get();

        // Cargar certificados
        $certificates = Certificate::where('student_id', $student->id)
            ->with('course')
            ->orderBy('issued_at', 'desc')
            ->get();

        // Calcular estadísticas
        $stats = [
            'total_enrollments' => $activeEnrollments->count(),
            'completed_courses' => Enrollment::where('student_id', $student->id)
                ->where('status', 'completed')
                ->count(),
            'total_payments' => Payment::whereHas('enrollment', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })->sum('amount'),
            'pending_balance' => $activeEnrollments->sum(function ($enrollment) {
                return $enrollment->paymentPlan ? $enrollment->paymentPlan->balance : 0;
            }),
            'certificates_count' => $certificates->count(),
        ];

        // Próximas clases
        $upcomingSessions = collect();
        foreach ($activeEnrollments as $enrollment) {
            foreach ($enrollment->courseOffering->sessions as $session) {
                if ($session->session_date >= now()) {
                    $upcomingSessions->push([
                        'session' => $session,
                        'course' => $enrollment->courseOffering->course,
                        'enrollment' => $enrollment,
                    ]);
                }
            }
        }
        $upcomingSessions = $upcomingSessions->sortBy('session.session_date')->take(5);

        // Material didáctico disponible
        $materials = collect();
        foreach ($activeEnrollments as $enrollment) {
            $courseMaterials = CourseMaterial::where('course_offering_id', $enrollment->course_offering_id)
                ->available()
                ->ordered()
                ->get();

            foreach ($courseMaterials as $material) {
                $materials->push([
                    'material' => $material,
                    'course' => $enrollment->courseOffering->course,
                ]);
            }
        }

        // Menús disponibles para selección
        $availableMenus = collect();
        foreach ($activeEnrollments as $enrollment) {
            $menus = MealMenu::where('course_offering_id', $enrollment->course_offering_id)
                ->with(['options' => function($query) {
                    $query->active();
                }])
                ->active()
                ->upcoming()
                ->orderBy('meal_date')
                ->get();

            foreach ($menus as $menu) {
                $availableMenus->push([
                    'menu' => $menu,
                    'enrollment' => $enrollment,
                    'course' => $enrollment->courseOffering->course,
                ]);
            }
        }

        return view('student-dashboard.index', compact(
            'student',
            'activeEnrollments',
            'payments',
            'certificates',
            'stats',
            'upcomingSessions',
            'materials',
            'availableMenus'
        ));
    }

    public function select()
    {
        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        return view('student-dashboard.select', compact('students'));
    }

    public function requestCourse(Request $request, $studentId)
    {
        $validated = $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $student = Student::findOrFail($studentId);

        // Crear un lead o una solicitud pendiente
        // Por ahora, simplemente redirigir con un mensaje
        return redirect()
            ->route('student-dashboard.index', $student->id)
            ->with('success', 'Solicitud de inscripción enviada. Te contactaremos pronto.');
    }

    public function selectMeal(Request $request, $studentId)
    {
        $validated = $request->validate([
            'meal_menu_id' => 'required|exists:meal_menus,id',
            'meal_option_id' => 'required|exists:meal_options,id',
            'enrollment_id' => 'required|exists:enrollments,id',
            'notes' => 'nullable|string|max:250',
        ]);

        $student = Student::findOrFail($studentId);

        // Verificar que la inscripción pertenece al estudiante
        $enrollment = Enrollment::where('id', $validated['enrollment_id'])
            ->where('student_id', $student->id)
            ->firstOrFail();

        $mealMenu = MealMenu::findOrFail($validated['meal_menu_id']);
        $mealOption = MealOption::findOrFail($validated['meal_option_id']);

        // Verificar disponibilidad
        if (!$mealMenu->canSelect()) {
            return redirect()
                ->route('student-dashboard.index', $student->id)
                ->with('error', 'Este menú ya no está disponible para selección.');
        }

        if (!$mealOption->isAvailable()) {
            return redirect()
                ->route('student-dashboard.index', $student->id)
                ->with('error', 'Esta opción ya no está disponible.');
        }

        // Crear o actualizar la selección
        \App\Models\MealSelection::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'meal_menu_id' => $mealMenu->id,
            ],
            [
                'meal_option_id' => $mealOption->id,
                'notes' => $validated['notes'],
            ]
        );

        return redirect()
            ->route('student-dashboard.index', $student->id)
            ->with('success', 'Selección de menú guardada exitosamente.');
    }
}
