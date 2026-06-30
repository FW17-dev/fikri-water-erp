<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('supplier');
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        $products = $query->paginate(15);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('products.form', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'reward_points' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'is_active' => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        return view('products.form', compact('product', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'reward_points' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'is_active' => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Produk diupdate!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk dihapus!');
    }
}