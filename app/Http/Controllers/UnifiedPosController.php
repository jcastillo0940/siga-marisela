<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Student;
use App\Models\Product;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class UnifiedPosController extends Controller
{
    public function index(Request $request)
    {
        // Get active cash register
        $activeCashRegister = CashRegister::where('opened_by', auth()->id())
            ->whereNull('closed_at')
            ->first();
        if (!$activeCashRegister) {
            return redirect()->route('cash-registers.index')
                ->with('error', 'Debes abrir una caja antes de usar el POS');
        }

        // Get products
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get student if selected
        $student = null;
        $enrollments = collect();
        
        if ($request->has('student_id')) {
            $student = Student::with(['enrollments.courseOffering.course', 'enrollments.paymentPlan.schedules'])
                ->findOrFail($request->student_id);
            
            // Get enrollments with pending payments
            $enrollments = $student->enrollments()
                ->whereHas('paymentPlan.schedules', function($query) {
                    $query->where('status', 'pending');
                })
                ->with(['courseOffering.course', 'paymentPlan.schedules' => function($query) {
                    $query->where('status', 'pending')
                        ->orderBy('due_date');
                }])
                ->get();
        }

        return view('pos.unified', compact(
            'activeCashRegister',
            'products',
            'student',
            'enrollments'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'students' => [],
                'products' => []
            ]);
        }

        // Search students with pending payments
        $students = Student::where(function($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('identification', 'LIKE', "%{$query}%");
            })
            ->whereHas('enrollments.paymentPlan.schedules', function($q) {
                $q->where('status', 'pending');
            })
            ->select('id', 'first_name', 'last_name', 'identification')
            ->limit(10)
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'type' => 'student',
                    'name' => $student->full_name,
                    'identification' => $student->identification,
                    'icon' => '👤'
                ];
            });

        // Search products
        $products = Product::where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'price', 'track_inventory', 'stock')
            ->limit(10)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'type' => 'product',
                    'name' => $product->name,
                    'price' => $product->price,
                    'stock' => $product->track_inventory ? $product->stock : null,
                    'icon' => '📦'
                ];
            });

        return response()->json([
            'students' => $students,
            'products' => $products
        ]);
    }
}