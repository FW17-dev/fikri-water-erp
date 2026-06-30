@extends('layouts.app')
@section('title', 'Keuangan')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Keuangan</h1>
    <a href="{{ route('financials.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">+ Tambah Transaksi</a>
</div>

<!-- Ringkasan -->
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

<!-- Grafik 7 hari -->
@if($dailyData->count() > 0)
<div class="bg-white p-4 rounded shadow mb-6">
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
@endif

    </div>
</div>

<!-- Filter -->
<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input type="text" name="search" placeholder="Cari deskripsi..." class="px-4 py-2 border rounded-lg" value="{{ request('search') }}">
    <select name="category" class="px-4 py-2 border rounded-lg">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
        @endforeach
    </select>
    <select name="type" class="px-4 py-2 border rounded-lg">
        <option value="">Semua Jenis</option>
        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
    </select>
    <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-4 py-2 border rounded-lg">
    <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-4 py-2 border rounded-lg">
    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded">Filter</button>
    <a href="{{ route('financials.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Reset</a>
</form>

<!-- Tabel -->
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Tanggal</th>
                <th class="p-2 text-left">Kategori</th>
                <th class="p-2 text-left">Sub Kategori</th>
                <th class="p-2 text-left">Deskripsi</th>
                <th class="p-2 text-left">Jenis</th>
                <th class="p-2 text-right">Jumlah</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($financials as $item)
            <tr class="border-b">
                <td class="p-2">{{ \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') }}</td>
                <td>{{ ucfirst($item->category) }}</td>
                <td>{{ $item->sub_category ?? '-' }}</td>
                <td>{{ $item->description }}</td>
                <td>
                    <span class="px-2 py-1 rounded text-xs {{ $item->type == 'income' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ $item->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                    </span>
                </td>
                <td class="text-right font-medium {{ $item->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                    Rp {{ number_format($item->amount, 0, ',', '.') }}
                </td>
                <td>
                    <a href="{{ route('financials.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('financials.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="p-4 text-center text-gray-500">Belum ada transaksi keuangan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $financials->links() }}</div>
@endsection