@extends('layouts.app')
@section('title', 'Buat Order Baru')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Buat Order Baru</h1>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Customer</label>
            <select name="customer_id" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Pilih Customer</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Produk</label>
            <select name="product_id" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
            <input type="number" name="quantity" value="1" min="1" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Kurir (Opsional)</label>
            <select name="courier_id" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Pilih Kurir</option>
                @foreach($couriers as $courier)
                <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Metode Pembayaran</label>
            <select name="payment_method" class="w-full px-4 py-2 border rounded-lg">
                <option value="cash">Cash</option>
                <option value="transfer">Transfer</option>
                <option value="debt">Piutang</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Catatan</label>
            <textarea name="notes" class="w-full px-4 py-2 border rounded-lg"></textarea>
        </div>

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Simpan Order</button>
    </form>
</div>
@endsection