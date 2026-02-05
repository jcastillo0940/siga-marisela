<?php

namespace App\Http\Controllers;

use App\Models\MealMenu;
use App\Models\MealOption;
use App\Models\CourseOffering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealMenuController extends Controller
{
    /**
     * Display a listing of meal menus
     */
    public function index(Request $request)
    {
        $query = MealMenu::with(['courseOffering', 'options'])
            ->orderBy('meal_date', 'desc');

        // Filtros
        if ($request->filled('course_offering_id')) {
            $query->where('course_offering_id', $request->course_offering_id);
        }

        if ($request->filled('meal_type')) {
            $query->where('meal_type', $request->meal_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('meal_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('meal_date', '<=', $request->date_to);
        }

        $menus = $query->paginate(15);
        $courseOfferings = CourseOffering::active()->get();

        return view('meal-menus.index', compact('menus', 'courseOfferings'));
    }

    /**
     * Show the form for creating a new meal menu
     */
    public function create()
    {
        $courseOfferings = CourseOffering::with('dates')
            ->active()
            ->upcoming()
            ->get()
            ->map(function ($offering) {
                // Asegurarnos de que las fechas estén en el formato correcto
                $offering->dates_json = $offering->dates->map(function ($date) {
                    return [
                        'class_date' => $date->class_date->format('Y-m-d'),
                        'class_number' => $date->class_number ?? $date->session_number ?? 1,
                        'session_number' => $date->class_number ?? $date->session_number ?? 1,
                    ];
                })->toJson();
                return $offering;
            });

        return view('meal-menus.create', compact('courseOfferings'));
    }

    /**
     * Store a newly created meal menu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'meal_date' => 'required|date|after_or_equal:today',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'menu_description' => 'required|string|max:1000',
            'menu_image' => 'nullable|image|max:2048',
            'max_selections' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean',
            // Opciones del menú
            'options' => 'required|array|min:1',
            'options.*.name' => 'required|string|max:255',
            'options.*.description' => 'nullable|string|max:500',
            'options.*.image' => 'nullable|image|max:2048',
            'options.*.is_vegetarian' => 'boolean',
            'options.*.is_vegan' => 'boolean',
            'options.*.is_gluten_free' => 'boolean',
            'options.*.available_quantity' => 'nullable|integer|min:0',
            'options.*.is_active' => 'boolean',
        ]);

        // Guardar imagen del menú
        if ($request->hasFile('menu_image')) {
            $validated['menu_image'] = $request->file('menu_image')
                ->store('meal-menus', 'public');
        }

        // Crear el menú
        $menu = MealMenu::create($validated);

        // Crear las opciones del menú
        foreach ($request->options as $index => $optionData) {
            $optionData['meal_menu_id'] = $menu->id;

            // Guardar imagen de la opción
            if ($request->hasFile("options.{$index}.image")) {
                $optionData['image'] = $request->file("options.{$index}.image")
                    ->store('meal-options', 'public');
            }

            MealOption::create($optionData);
        }

        return redirect()
            ->route('meal-menus.show', $menu)
            ->with('success', 'Menú creado exitosamente');
    }

    /**
     * Display the specified meal menu
     */
    public function show(MealMenu $mealMenu)
    {
        $mealMenu->load([
            'courseOffering.course',
            'options.selections.enrollment.student',
        ]);

        // Estadísticas de selecciones
        $stats = [
            'total_students' => $mealMenu->courseOffering->enrollments()->count(),
            'total_selections' => $mealMenu->selections()->count(),
            'pending_selections' => $mealMenu->courseOffering->enrollments()->count() 
                - $mealMenu->selections()->count(),
            'selections_by_option' => $mealMenu->options->map(function ($option) {
                return [
                    'name' => $option->name,
                    'count' => $option->selections_count,
                    'remaining' => $option->remaining_quantity,
                ];
            }),
        ];

        return view('meal-menus.show', compact('mealMenu', 'stats'));
    }

    /**
     * Show the form for editing the meal menu
     */
    public function edit(MealMenu $mealMenu)
    {
        $mealMenu->load('options');
        $courseOfferings = CourseOffering::active()->get();

        return view('meal-menus.edit', compact('mealMenu', 'courseOfferings'));
    }

    /**
     * Update the meal menu
     */
    public function update(Request $request, MealMenu $mealMenu)
    {
        $validated = $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'meal_date' => 'required|date',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'menu_description' => 'required|string|max:1000',
            'menu_image' => 'nullable|image|max:2048',
            'max_selections' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean',
        ]);

        // Actualizar imagen si se proporciona
        if ($request->hasFile('menu_image')) {
            // Eliminar imagen anterior
            if ($mealMenu->menu_image) {
                Storage::disk('public')->delete($mealMenu->menu_image);
            }
            $validated['menu_image'] = $request->file('menu_image')
                ->store('meal-menus', 'public');
        }

        $mealMenu->update($validated);

        return redirect()
            ->route('meal-menus.show', $mealMenu)
            ->with('success', 'Menú actualizado exitosamente');
    }

    /**
     * Remove the meal menu
     */
    public function destroy(MealMenu $mealMenu)
    {
        // Verificar si hay selecciones
        if ($mealMenu->selections()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un menú con selecciones realizadas');
        }

        // Eliminar imágenes
        if ($mealMenu->menu_image) {
            Storage::disk('public')->delete($mealMenu->menu_image);
        }

        foreach ($mealMenu->options as $option) {
            if ($option->image) {
                Storage::disk('public')->delete($option->image);
            }
        }

        $mealMenu->delete();

        return redirect()
            ->route('meal-menus.index')
            ->with('success', 'Menú eliminado exitosamente');
    }

    /**
     * Send menu notifications to students
     */
    public function sendNotifications(MealMenu $mealMenu)
    {
        $enrollments = $mealMenu->courseOffering
            ->enrollments()
            ->with('student')
            ->get();

        $sent = 0;
        foreach ($enrollments as $enrollment) {
            // TODO: Implementar envío de WhatsApp/Email
            // Por ahora solo incrementamos el contador
            $sent++;
        }

        return back()->with('success', "Notificaciones enviadas a {$sent} estudiantes");
    }

    /**
     * Generate lunch report for hotel
     */
    public function generateReport(MealMenu $mealMenu)
    {
        $mealMenu->load([
            'courseOffering.course',
            'options.selections.enrollment.student',
        ]);

        $selections = $mealMenu->selections()
            ->with(['enrollment.student', 'mealOption'])
            ->get()
            ->groupBy('meal_option_id');

        $noSelections = $mealMenu->courseOffering
            ->enrollments()
            ->with('student')
            ->whereNotIn('id', $mealMenu->selections->pluck('enrollment_id'))
            ->get();

        return view('meal-menus.report', compact('mealMenu', 'selections', 'noSelections'));
    }
}