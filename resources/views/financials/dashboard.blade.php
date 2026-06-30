@extends('layouts.app')
@section('title', 'Dashboard Keuangan')
@section('content')
<h1 class="text-2xl font-bold mb-4">Dashboard Keuangan</h1>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-green-100 p-4 rounded shadow">
        <p class="text-gray-600 text-sm">Total Pemasukan</p>
        <p class="text-xl font-bold text-green-700">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
    </div>
    <div class="bg-red-100 p-4 rounded shadow">
        <p class="text-gray-600 text-sm">Total Pengeluaran</p>
        <p class="text-xl font-bold text-red-700">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
    </div>
    <div class="bg-blue-100 p-4 rounded shadow">
        <p class="text-gray-600 text-sm">Saldo Kas</p>
        <p class="text-xl font-bold text-blue-700">Rp {{ number_format($balance, 0, ',', '.') }}</p>
    </div>
</div>

<!-- Per kategori -->
<div class="grid grid-cols-2 gap-4 mb-6">
    @foreach($categoryData as $cat => $data)
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold text-lg capitalize">{{ $cat }}</h3>
        <p class="text-green-600">Pemasukan: Rp {{ number_format($data['income'], 0, ',', '.') }}</p>
        <p class="text-red-600">Pengeluaran: Rp {{ number_format($data['expense'], 0, ',', '.') }}</p>
        <p class="font-bold {{ ($data['income'] - $data['expense']) >= 0 ? 'text-green-700' : 'text-red-700' }}">
            Saldo: Rp {{ number_format($data['income'] - $data['expense'], 0, ',', '.') }}
        </p>
    </div>
    @endforeach
</div>

<!-- Grafik sederhana (7 hari terakhir) -->
<div class="bg-white p-4 rounded shadow">
    <h3 class="font-semibold text-lg mb-2">7 Hari Terakhir</h3>
    <div class="flex flex-wrap gap-4">
        @php
            $days = $dailyData->groupBy('date');
        @endphp
        @foreach($days as $date => $items)
        <div class="bg-gray-100 p-2 rounded text-center min-w-[80px]">
            <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</p>
            @php
                $income = $items->where('type', 'income')->sum('total');
                $expense = $items->where('type', 'expense')->sum('total');
            @endphp
            <p class="text-green-600 text-sm">+{{ number_format($income, 0, ',', '.') }}</p>
            <p class="text-red-600 text-sm">-{{ number_format($expense, 0, ',', '.') }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection