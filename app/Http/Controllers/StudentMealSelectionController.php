<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MealMenu;
use App\Models\MealSelection;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentMealSelectionController extends Controller
{
    /**
     * Show meal selection form for student
     */
    public function show($token)
    {
        // Decodificar token: enrollment_id|meal_menu_id|hash
        $decoded = $this->decodeToken($token);
        
        if (!$decoded) {
            abort(404, 'Link inválido o expirado');
        }

        [$enrollmentId, $mealMenuId] = $decoded;

        $enrollment = Enrollment::with(['student', 'courseOffering.course'])
            ->findOrFail($enrollmentId);

        $mealMenu = MealMenu::with(['options' => function ($query) {
            $query->active()->orderBy('name');
        }])
            ->findOrFail($mealMenuId);

        // Verificar que el menú pertenece al curso del estudiante
        if ($mealMenu->course_offering_id !== $enrollment->course_offering_id) {
            abort(403, 'Este menú no corresponde a tu curso');
        }

        // Verificar si ya venció el plazo
        if (!$mealMenu->canSelect()) {
            return view('public.meal-selection.expired', compact('mealMenu'));
        }

        // Obtener selección existente
        $existingSelection = $mealMenu->getStudentSelection($enrollmentId);

        return view('public.meal-selection.form', compact(
            'enrollment',
            'mealMenu',
            'existingSelection'
        ));
    }

    /**
     * Store or update student's meal selection
     */
    public function store(Request $request, $token)
    {
        $decoded = $this->decodeToken($token);
        
        if (!$decoded) {
            abort(404, 'Link inválido o expirado');
        }

        [$enrollmentId, $mealMenuId] = $decoded;

        $enrollment = Enrollment::findOrFail($enrollmentId);
        $mealMenu = MealMenu::findOrFail($mealMenuId);

        // Verificar que el menú pertenece al curso
        if ($mealMenu->course_offering_id !== $enrollment->course_offering_id) {
            abort(403);
        }

        // Verificar que aún se puede seleccionar
        if (!$mealMenu->canSelect()) {
            return back()->with('error', 'El plazo para seleccionar ya expiró');
        }

        $validated = $request->validate([
            'meal_option_id' => 'nullable|exists:meal_options,id',
            'notes' => 'nullable|string|max:500',
            'no_meal' => 'boolean',
        ]);

        // Si marcó "no quiero almuerzo"
        if ($request->boolean('no_meal')) {
            $validated['meal_option_id'] = null;
        } else {
            // Validar que la opción seleccionada pertenece a este menú
            $option = $mealMenu->options()->find($validated['meal_option_id']);
            
            if (!$option) {
                return back()->with('error', 'Opción de menú inválida');
            }

            // Verificar disponibilidad
            if (!$option->isAvailable()) {
                return back()->with('error', 'Esta opción ya no está disponible');
            }
        }

        // Crear o actualizar selección
        MealSelection::updateOrCreate(
            [
                'enrollment_id' => $enrollmentId,
                'meal_menu_id' => $mealMenuId,
            ],
            [
                'meal_option_id' => $validated['meal_option_id'],
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return view('public.meal-selection.success', compact('mealMenu', 'enrollment'));
    }

    /**
     * Generate secure token for meal selection
     */
    public static function generateToken(int $enrollmentId, int $mealMenuId): string
    {
        $data = "{$enrollmentId}|{$mealMenuId}";
        $hash = hash_hmac('sha256', $data, config('app.key'));
        
        return base64_encode("{$data}|{$hash}");
    }

    /**
     * Decode and validate token
     */
    private function decodeToken(string $token): ?array
    {
        try {
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);

            if (count($parts) !== 3) {
                return null;
            }

            [$enrollmentId, $mealMenuId, $providedHash] = $parts;

            // Verificar hash
            $data = "{$enrollmentId}|{$mealMenuId}";
            $expectedHash = hash_hmac('sha256', $data, config('app.key'));

            if (!hash_equals($expectedHash, $providedHash)) {
                return null;
            }

            return [(int) $enrollmentId, (int) $mealMenuId];
        } catch (\Exception $e) {
            return null;
        }
    }
}
