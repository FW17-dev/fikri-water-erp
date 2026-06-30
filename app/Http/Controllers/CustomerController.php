<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Area;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('area');
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
        }
        $customers = $query->paginate(15);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $areas = Area::all();
        return view('customers.form', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:customers',
            'address' => 'nullable',
            'area_id' => 'nullable|exists:areas,id',
            'notes' => 'nullable',
        ]);
        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan!');
    }

    public function edit(Customer $customer)
    {
        $areas = Area::all();
        return view('customers.form', compact('customer', 'areas'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:customers,phone,' . $customer->id,
            'address' => 'nullable',
            'area_id' => 'nullable|exists:areas,id',
            'notes' => 'nullable',
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', 'Customer diupdate!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Customer dihapus!');
    }
}