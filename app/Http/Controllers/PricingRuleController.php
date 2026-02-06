<?php

namespace App\Http\Controllers;

use App\Models\CourseOffering;
use App\Models\PricingRule;
use Illuminate\Http\Request;

class PricingRuleController extends Controller
{
    /**
     * Guardar una nueva regla de precio.
     */
    public function store(Request $request, CourseOffering $courseOffering)
    {
        $validated = $request->validate([
            'min_students' => 'required|integer|min:1',
            'max_students' => 'nullable|integer|gte:min_students',
            'type' => 'required|in:fixed_total_price,percentage,fixed_discount',
            'value' => 'required|numeric|min:0',
            'name' => 'nullable|string|max:255',
        ]);

        $courseOffering->pricingRules()->create($validated);

        return back()->with('success', 'Regla de precio agregada correctamente.');
    }

    /**
     * Eliminar una regla existente.
     */
    public function destroy(PricingRule $pricingRule)
    {
        $pricingRule->delete();
        return back()->with('success', 'Regla de precio eliminada.');
    }
}