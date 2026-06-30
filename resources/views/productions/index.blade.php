@extends('layouts.app')
@section('title', 'Data Produksi')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Data Produksi Air</h1>
    <a href="{{ route('productions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah</a>
</div>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Supplier</th>
                <th class="p-2 text-left">Jumlah</th>
                <th class="p-2 text-left">Harga/Unit</th>
                <th class="p-2 text-left">Total</th>
                <th class="p-2 text-left">Tanggal</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productions as $p)
            <tr>
                <td>{{ $p->supplier->name }}</td>
                <td>{{ $p->quantity }}</td>
                <td>Rp {{ number_format($p->price_per_unit, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($p->total_price, 0, ',', '.') }}</td>
                <td>{{ $p->purchase_date }}</td>
                <td>
                    <a href="{{ route('productions.edit', $p) }}" class="text-blue-600">Edit</a>
                    <form action="{{ route('productions.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-4 text-center">Belum ada data produksi</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $productions->links() }}</div>
@endsection