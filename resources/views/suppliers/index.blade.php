@extends('layouts.app')
@section('title', 'Daftar Supplier')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">🏭 Daftar Supplier</h1>
    <a href="{{ route('suppliers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">+ Tambah Supplier</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
@endif

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Nama</th>
                <th class="p-2 text-left">No. HP</th>
                <th class="p-2 text-left">Alamat</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-2">{{ $supplier->name }}</td>
                <td>{{ $supplier->phone ?? '-' }}</td>
                <td>{{ $supplier->address ?? '-' }}</td>
                <td>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus supplier ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada supplier</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $suppliers->links() }}</div>
@endsection