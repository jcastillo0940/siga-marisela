<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\MealMenu;
use App\Models\MealSelection;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    /**
     * Show student selection page
     */
    public function select()
    {
        $students = Student::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('student-dashboard.select', compact('students'));
    }

    /**
     * Show student dashboard
     */
    public function index(Student $student)
    {
        // Cargar relaciones necesarias
        $student->load([
            'enrollments' => function ($query) {
                $query->whereIn('status', ['active', 'completed'])
                    ->with([
                        'courseOffering.course',
                        'courseOffering.sessions',
                        'paymentPlan.schedules',
                        'attendances'
                    ]);
            },
            'certificates.course'
        ]);

        // Estadísticas
        $stats = [
            'total_enrollments' => $student->enrollments->where('status', 'active')->count(),
            'completed_courses' => $student->enrollments->where('status', 'completed')->count(),
            'total_payments' => $student->enrollments->flatMap->paymentPlan->flatMap->schedules
                ->where('status', 'paid')->sum('amount'),
            'pending_balance' => $student->enrollments->flatMap->paymentPlan->sum('balance'),
            'certificates_count' => $student->certificates->count(),
        ];

        // Inscripciones activas
        $activeEnrollments = $student->enrollments->where('status', 'active');

        // Próximas sesiones
        $upcomingSessions = [];
        foreach ($activeEnrollments as $enrollment) {
            foreach ($enrollment->courseOffering->sessions->where('session_date', '>=', today())->sortBy('session_date')->take(3) as $session) {
                $upcomingSessions[] = [
                    'enrollment' => $enrollment,
                    'session' => $session,
                    'course' => $enrollment->courseOffering->course,
                ];
            }
        }

        // Pagos recientes
        $payments = $student->enrollments->flatMap->paymentPlan->flatMap->schedules
            ->where('status', 'paid')
            ->sortByDesc('paid_at')
            ->take(5);

        // Certificados
        $certificates = $student->certificates()->with('course')->latest()->get();

        // Material didáctico
        $materials = [];
        foreach ($activeEnrollments as $enrollment) {
            if ($enrollment->courseOffering->course->materials) {
                foreach ($enrollment->courseOffering->course->materials as $material) {
                    $materials[] = [
                        'material' => $material,
                        'course' => $enrollment->courseOffering->course,
                    ];
                }
            }
        }

        // Menús disponibles para selección
        $availableMenus = $this->getAvailableMenusForStudent($student);

        return view('student-dashboard.index', compact(
            'student',
            'stats',
            'activeEnrollments',
            'upcomingSessions',
            'payments',
            'certificates',
            'materials',
            'availableMenus'
        ));
    }

    /**
     * Request course enrollment (método existente)
     */
    public function requestCourse(Request $request, Student $student)
    {
        // Tu implementación existente...
    }

    /**
     * Process meal selection from dashboard form
     */
    public function selectMeal(Request $request, Student $student)
    {
        $validated = $request->validate([
            'meal_menu_id' => 'required|exists:meal_menus,id',
            'enrollment_id' => 'required|exists:enrollments,id',
            'meal_option_id' => 'required|exists:meal_options,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Verificar que el enrollment pertenece al estudiante
        $enrollment = $student->enrollments()->find($validated['enrollment_id']);
        
        if (!$enrollment) {
            return back()->with('error', 'Inscripción no encontrada');
        }

        // Verificar que el menú pertenece al curso
        $mealMenu = MealMenu::find($validated['meal_menu_id']);
        
        if ($mealMenu->course_offering_id !== $enrollment->course_offering_id) {
            return back()->with('error', 'Este menú no corresponde a tu curso');
        }

        // Verificar que la opción está disponible
        $option = $mealMenu->options()
            ->where('id', $validated['meal_option_id'])
            ->where('is_active', true)
            ->first();

        if (!$option) {
            return back()->with('error', 'Opción de menú no disponible');
        }

        if ($option->available_quantity !== null && $option->remaining_quantity <= 0) {
            return back()->with('error', 'Esta opción ya no está disponible');
        }

        // Crear o actualizar selección
        MealSelection::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'meal_menu_id' => $mealMenu->id,
            ],
            [
                'meal_option_id' => $validated['meal_option_id'],
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return back()->with('success', '¡Selección de menú guardada exitosamente!');
    }

    /**
     * Get available menus for student selection
     */
    private function getAvailableMenusForStudent(Student $student)
    {
        $activeEnrollments = $student->enrollments()->where('status', 'active')->get();
        $courseOfferingIds = $activeEnrollments->pluck('course_offering_id');

        // Obtener menús futuros/activos para los cursos del estudiante
        $menus = MealMenu::whereIn('course_offering_id', $courseOfferingIds)
            ->where('is_active', true)
            ->where('meal_date', '>=', today())
            ->with(['courseOffering.course', 'options'])
            ->orderBy('meal_date', 'asc')
            ->get();

        // Estructurar la data como espera la vista
        $availableMenus = [];
        foreach ($menus as $menu) {
            $enrollment = $activeEnrollments->firstWhere('course_offering_id', $menu->course_offering_id);
            
            if ($enrollment) {
                $availableMenus[] = [
                    'menu' => $menu,
                    'enrollment' => $enrollment,
                    'course' => $enrollment->courseOffering->course,
                ];
            }
        }

        return collect($availableMenus);
    }
}