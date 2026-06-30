@extends('layouts.app')
@section('title', isset($area) ? 'Edit Area' : 'Tambah Area')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ isset($area) ? 'Edit' : 'Tambah' }} Area</h1>
    <form action="{{ isset($area) ? route('areas.update', $area) : route('areas.store') }}" method="POST">
        @csrf
        @if(isset($area)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Area</label>
            <input type="text" name="name" value="{{ old('name', $area->name ?? '') }}" 
                   class="w-full px-4 py-2 border rounded-lg" 
                   placeholder="Contoh: Kampung Melayu" required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection