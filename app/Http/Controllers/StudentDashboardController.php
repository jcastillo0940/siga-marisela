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
     * Lógica de redirección principal.
     */
    public function root()
    {
        $user = Auth::user();

        if ($user->hasRole('student')) {
            if ($user->student) {
                return $this->index($user->student);
            }
            abort(403, 'Tu usuario no tiene un perfil de estudiante asociado.');
        }

        if ($user->hasAnyRole(['super-admin', 'admin', 'staff'])) {
            return $this->select();
        }

        abort(403, 'No tienes permisos para acceder al dashboard de estudiantes.');
    }

    public function select()
    {
        $students = Student::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('student-dashboard.select', compact('students'));
    }

    /**
     * Vista Integral del Dashboard del Estudiante
     */
    public function index(Student $student)
    {
        $user = Auth::user();

        // SEGURIDAD
        if ($user->hasRole('student') && $user->student && $user->student->id !== $student->id) {
            abort(403, 'No estás autorizado para ver esta información.');
        }

        // CARGA INTEGRAL: Incluye todos los estados relevantes
        $student->load([
            'enrollments' => function ($query) {
                // Ajustamos para incluir los estados reales de tu DB
                $query->whereIn('status', ['active', 'inscrito', 'en_curso', 'completed', 'cancelled'])
                    ->with([
                        'courseOffering.course',
                        'courseOffering.materials',
                        'courseOffering.sessions',
                        'paymentPlan.schedules',
                        'attendances',
                        'payments' 
                    ]);
            },
            'certificates.course'
        ]);

        // 1. Estadísticas Consolidadas
        $stats = [
            'total_enrollments' => $student->enrollments->whereIn('status', ['active', 'inscrito', 'en_curso'])->count(),
            'completed_courses' => $student->enrollments->where('status', 'completed')->count(),
            'total_payments' => $student->enrollments->flatMap->payments->sum('amount'),
            'pending_balance' => $student->enrollments
                ->whereIn('status', ['active', 'inscrito', 'en_curso'])
                ->flatMap->paymentPlan
                ->sum('balance'),
            'certificates_count' => $student->certificates->count(),
        ];

        // 2. Clasificación de Cursos (Activos vs Históricos)
        $activeEnrollments = $student->enrollments->whereIn('status', ['active', 'inscrito', 'en_curso']);
        $pastEnrollments = $student->enrollments->whereIn('status', ['completed', 'cancelled']);

        // 3. Próximas Sesiones (Próximas 5 sesiones)
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
        $upcomingSessions = collect($upcomingSessionsData)
            ->sortBy(function ($item) { 
                return $item['session']->session_date; 
            })
            ->take(5)
            ->values();

        // 4. Historial de Pagos Recientes (Top 10)
        $payments = $student->enrollments
            ->flatMap->payments
            ->sortByDesc('payment_date')
            ->take(10);

        // 5. Material Didáctico (Consolidado de cursos activos)
        $materialsData = [];
        foreach ($activeEnrollments as $enrollment) {
            // Se asume que la relación en CourseOffering se llama 'materials'
            $offeringMaterials = $enrollment->courseOffering->materials ?? collect();
            foreach ($offeringMaterials as $material) {
                $materialsData[] = [
                    'material' => $material,
                    'course' => $enrollment->courseOffering->course,
                ];
            }
        }
        $materials = collect($materialsData);

        // 6. Certificados Emitidos
        $certificates = $student->certificates->sortByDesc('issued_at');

        // 7. Menús Disponibles
        $availableMenus = $this->getAvailableMenusForStudent($student);

        return view('student-dashboard.index', compact(
            'student', 
            'stats', 
            'activeEnrollments', 
            'pastEnrollments',
            'upcomingSessions', 
            'payments', 
            'certificates', 
            'materials', 
            'availableMenus'
        ));
    }

    /**
     * Lógica de Menús (Solo activos y futuros)
     */
    private function getAvailableMenusForStudent(Student $student)
    {
        // Filtramos por estados activos
        $activeOfferingIds = $student->enrollments()
            ->whereIn('status', ['active', 'inscrito', 'en_curso'])
            ->pluck('course_offering_id');

        $menus = MealMenu::whereIn('course_offering_id', $activeOfferingIds)
            ->where('is_active', true)
            ->where('meal_date', '>=', today())
            ->with(['courseOffering.course', 'options'])
            ->orderBy('meal_date', 'asc')
            ->get();

        return $menus->map(function($menu) use ($student) {
            $enrollment = $student->enrollments
                ->whereIn('status', ['active', 'inscrito', 'en_curso'])
                ->where('course_offering_id', $menu->course_offering_id)
                ->first();
            return [
                'menu' => $menu,
                'enrollment' => $enrollment,
                'course' => $menu->courseOffering->course,
            ];
        });
    }

    public function selectMeal(Request $request, Student $student)
    {
        $validated = $request->validate([
            'meal_menu_id' => 'required|exists:meal_menus,id',
            'enrollment_id' => 'required|exists:enrollments,id',
            'meal_option_id' => 'required|exists:meal_options,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $enrollment = $student->enrollments()->find($validated['enrollment_id']);
        if (!$enrollment) {
            return back()->with('error', 'Inscripción no válida');
        }

        MealSelection::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id, 
                'meal_menu_id' => $validated['meal_menu_id']
            ],
            [
                'meal_option_id' => $validated['meal_option_id'], 
                'notes' => $validated['notes']
            ]
        );

        return back()->with('success', 'Menú seleccionado correctamente.');
    }
}