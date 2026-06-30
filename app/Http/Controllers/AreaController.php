<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::orderBy('name')->paginate(15);
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        return view('areas.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:areas,name',
        ]);

        Area::create($request->all());

        return redirect()->route('areas.index')
            ->with('success', 'Area berhasil ditambahkan!');
    }

    public function edit(Area $area)
    {
        return view('areas.form', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:areas,name,' . $area->id,
        ]);

        $area->update($request->all());

        return redirect()->route('areas.index')
            ->with('success', 'Area berhasil diupdate!');
    }

    public function destroy(Area $area)
    {
        // Cek apakah ada customer yang menggunakan area ini
        if ($area->customers()->count() > 0) {
            return back()->with('error', 'Area tidak bisa dihapus karena masih digunakan oleh customer!');
        }

        $area->delete();
        return back()->with('success', 'Area berhasil dihapus!');
    }
}