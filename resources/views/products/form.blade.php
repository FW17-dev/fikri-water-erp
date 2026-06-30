@extends('layouts.app')
@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ isset($product) ? 'Edit' : 'Tambah' }} Produk</h1>
    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
            <input type="text" name="category" value="{{ old('category', $product->category ?? '') }}" class="w-full px-4 py-2 border rounded-lg" placeholder="Misal: Air Minum">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required min="0">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Poin Reward (per pembelian)</label>
            <input type="number" name="reward_points" value="{{ old('reward_points', $product->reward_points ?? 1) }}" class="w-full px-4 py-2 border rounded-lg" min="0">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
            <select name="supplier_id" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Pilih Supplier</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ (old('supplier_id', $product->supplier_id ?? '') == $supplier->id) ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4 flex items-center">
            <input type="checkbox" name="is_active" value="1" {{ (old('is_active', $product->is_active ?? true) == 1) ? 'checked' : '' }} class="mr-2">
            <label class="text-gray-700 text-sm font-bold">Aktif</label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection