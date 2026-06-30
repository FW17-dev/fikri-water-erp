@extends('layouts.app')
@section('title', isset($production) ? 'Edit Produksi' : 'Tambah Produksi')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ isset($production) ? 'Edit' : 'Tambah' }} Produksi</h1>
    <form action="{{ isset($production) ? route('productions.update', $production) : route('productions.store') }}" method="POST">
        @csrf @if(isset($production)) @method('PUT') @endif
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
            <select name="supplier_id" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Pilih Supplier</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ (old('supplier_id', $production->supplier_id ?? '') == $supplier->id) ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Galon</label>
            <input type="number" name="quantity" value="{{ old('quantity', $production->quantity ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required min="1">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Harga per Unit (Rp)</label>
            <input type="number" name="price_per_unit" value="{{ old('price_per_unit', $production->price_per_unit ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required min="0">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pembelian</label>
            <input type="date" name="purchase_date" value="{{ old('purchase_date', $production->purchase_date ?? date('Y-m-d')) }}" class="w-full px-4 py-2 border rounded-lg" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection