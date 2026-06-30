@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500 text-sm">Pendapatan</p>
        <p class="text-xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500 text-sm">Customer</p>
        <p class="text-xl font-bold">{{ $totalCustomers }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500 text-sm">Piutang</p>
        <p class="text-xl font-bold text-red-600">Rp {{ number_format($totalDebt, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500 text-sm">Stok Galon</p>
        <p class="text-xl font-bold {{ $remainingProduction < 100 ? 'text-red-600' : 'text-green-600' }}">
            {{ $remainingProduction }}
        </p>
    </div>
</div>

<div class="bg-white p-4 rounded shadow">
    <h2 class="font-semibold text-lg mb-4">Order Terakhir</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Customer</th>
                    <th class="p-2 text-left">Produk</th>
                    <th class="p-2 text-left">Total</th>
                    <th class="p-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr class="border-b">
                    <td class="p-2">{{ $order->customer->name }}</td>
                    <td>{{ $order->product->name }}</td>
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
                </tr>
                @empty
                <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada order</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection