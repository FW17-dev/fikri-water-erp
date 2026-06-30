<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index(Request $request)
    {
        $query = Courier::query();
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        $couriers = $query->paginate(15);
        return view('couriers.index', compact('couriers'));
    }

    public function create()
    {
        return view('couriers.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        Courier::create($validated);
        return redirect()->route('couriers.index')->with('success', 'Kurir berhasil ditambahkan!');
    }

    public function edit(Courier $courier)
    {
        return view('couriers.form', compact('courier'));
    }

    public function update(Request $request, Courier $courier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        $courier->update($validated);
        return redirect()->route('couriers.index')->with('success', 'Kurir diupdate!');
    }

    public function destroy(Courier $courier)
    {
        $courier->delete();
        return back()->with('success', 'Kurir dihapus!');
    }
}