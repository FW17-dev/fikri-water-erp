@extends('layouts.app')
@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ isset($supplier) ? 'Edit' : 'Tambah' }} Supplier</h1>
    <form action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}" method="POST">
        @csrf
        @if(isset($supplier)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Supplier</label>
            <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" 
                   class="w-full px-4 py-2 border rounded-lg" 
                   placeholder="Contoh: PDAM Tirta" required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" 
                   class="w-full px-4 py-2 border rounded-lg" 
                   placeholder="021-1234567">
            @error('phone')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
            <textarea name="address" class="w-full px-4 py-2 border rounded-lg" 
                      placeholder="Jl. Raya No. 1, Jakarta">{{ old('address', $supplier->address ?? '') }}</textarea>
            @error('address')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection