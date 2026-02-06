<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\MealMenu;
use App\Models\MealSelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    /**
     * Lógica principal de redirección:
     * Si es alumno, va directo a su dashboard.
     * Si es admin/staff, va a la selección de alumno.
     */
    public function root()
    {
        $user = Auth::user();

        // 1. Si el usuario es estudiante y tiene perfil asociado, ir directo a su dashboard
        if ($user->hasRole('student')) {
            if ($user->student) {
                return $this->index($user->student);
            }
            abort(403, 'Tu usuario no tiene un perfil de estudiante asociado.');
        }

        // 2. Si es personal administrativo, permitir seleccionar estudiante
        if ($user->hasAnyRole(['super-admin', 'admin', 'staff'])) {
            return $this->select();
        }

        abort(403, 'No tienes permisos para acceder al dashboard de estudiantes.');
    }

    /**
     * Show student selection page (Para Admins/Staff)
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
        $user = Auth::user();

        // SEGURIDAD: Evitar que un estudiante vea el dashboard de otro
        // Si el usuario logueado es estudiante, verificar que el ID coincida
        if ($user->hasRole('student') && $user->student && $user->student->id !== $student->id) {
            abort(403, 'No estás autorizado para ver la información de este estudiante.');
        }

        // Cargar relaciones necesarias
        $student->load([
            'enrollments' => function ($query) {
                $query->whereIn('status', ['active', 'completed'])
                    ->with([
                        'courseOffering.course.materials',
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

        // --- CORRECCIÓN: Próximas sesiones como Collection ---
        $upcomingSessionsData = [];
        foreach ($activeEnrollments as $enrollment) {
            $futureSessions = $enrollment->courseOffering->sessions
                ->where('session_date', '>=', today())
                ->sortBy('session_date')
                ->take(3);

            foreach ($futureSessions as $session) {
                $upcomingSessionsData[] = [
                    'enrollment' => $enrollment,
                    'session' => $session,
                    'course' => $enrollment->courseOffering->course,
                ];
            }
        }
        // IMPORTANTE: Convertir a Collection y ordenar
        $upcomingSessions = collect($upcomingSessionsData)
            ->sortBy(function ($item) {
                return $item['session']->session_date;
            })
            ->take(3)
            ->values(); // Reindexa la colección

        // Pagos recientes
        $payments = $student->enrollments
            ->flatMap->paymentPlan
            ->flatMap->schedules
            ->where('status', 'paid')
            ->sortByDesc('paid_at')
            ->take(5);

        // Certificados
        $certificates = $student->certificates()
            ->with('course')
            ->latest()
            ->get();

        // --- CORRECCIÓN: Material didáctico como Collection ---
        $materialsData = [];
        foreach ($activeEnrollments as $enrollment) {
            $courseMaterials = $enrollment->courseOffering->course->materials ?? collect();
            
            foreach ($courseMaterials as $material) {
                $materialsData[] = [
                    'material' => $material,
                    'course' => $enrollment->courseOffering->course,
                ];
            }
        }
        // IMPORTANTE: Convertir a Collection
        $materials = collect($materialsData);

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
     * Request course enrollment
     */
    public function requestCourse(Request $request, Student $student)
    {
        // Redirigir al formulario público de leads o crear una solicitud interna
        // Por ahora redirigimos a la ruta de leads públicos
        return redirect()->route('public.leads.create');
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