@extends('layouts.app')
@section('title', 'Daftar Order')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Daftar Order</h1>
    <a href="{{ route('orders.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">+ Order Baru</a>
</div>

<form method="GET" class="mb-4">
    <input type="text" name="search" placeholder="Cari nama customer..." class="w-full md:w-1/3 px-4 py-2 border rounded-lg" value="{{ request('search') }}">
    <button type="submit" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded text-sm">Cari</button>
</form>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Customer</th>
                <th class="p-2 text-left">Produk</th>
                <th class="p-2 text-left">Jumlah</th>
                <th class="p-2 text-left">Total</th>
                <th class="p-2 text-left">Status</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="border-b">
                <td class="p-2">{{ $order->customer->name }}</td>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>
                    <span class="px-2 py-1 rounded text-xs 
                        @if($order->status == 'pending') bg-yellow-200 text-yellow-800
                        @elseif($order->status == 'processing') bg-blue-200 text-blue-800
                        @elseif($order->status == 'delivered') bg-green-200 text-green-800
                        @elseif($order->status == 'completed') bg-green-600 text-white
                        @else bg-red-200 text-red-800
                        @endif">
                        {{ $order->status }}
                    </span>
                </td>
                <td>
                    <form action="{{ route('orders.status', $order) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="border rounded text-xs px-2 py-1">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-4 text-center text-gray-500">Belum ada order. Yuk buat order!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection