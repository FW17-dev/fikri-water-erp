<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::with('supplier');
        if ($request->search) {
            $query->whereHas('supplier', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }
        $productions = $query->latest()->paginate(15);
        return view('productions.index', compact('productions'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('productions.form', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'quantity'       => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
            'purchase_date'  => 'required|date',
        ]);
        $validated['total_price'] = $validated['quantity'] * $validated['price_per_unit'];
        Production::create($validated);
        return redirect()->route('productions.index')->with('success', 'Data produksi berhasil ditambahkan!');
    }

    public function edit(Production $production)
    {
        $suppliers = Supplier::all();
        return view('productions.form', compact('production', 'suppliers'));
    }

    public function update(Request $request, Production $production)
    {
        $validated = $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'quantity'       => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
            'purchase_date'  => 'required|date',
        ]);
        $validated['total_price'] = $validated['quantity'] * $validated['price_per_unit'];
        $production->update($validated);
        return redirect()->route('productions.index')->with('success', 'Data produksi diupdate!');
    }

    public function destroy(Production $production)
    {
        $production->delete();
        return back()->with('success', 'Data produksi dihapus!');
    }
}