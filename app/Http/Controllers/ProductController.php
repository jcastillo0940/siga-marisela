<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(20);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ]);

        // Set checkbox values
        $validated['track_inventory'] = $request->has('track_inventory');
        $validated['active'] = $request->has('active');

        // If not tracking inventory, set stock to 0
        if (!$validated['track_inventory']) {
            $validated['stock'] = 0;
            $validated['min_stock'] = 0;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ]);

        // Set checkbox values
        $validated['track_inventory'] = $request->has('track_inventory');
        $validated['active'] = $request->has('active');

        // If not tracking inventory, set stock to 0
        if (!$validated['track_inventory']) {
            $validated['stock'] = 0;
            $validated['min_stock'] = 0;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado exitosamente');
    }

    public function toggleStatus(Product $product)
    {
        $product->active = !$product->active;
        $product->save();

        $status = $product->active ? 'activado' : 'desactivado';

        return redirect()->back()
            ->with('success', "Producto {$status} exitosamente");
    }
}
