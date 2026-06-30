@extends('layouts.app')
@section('title', 'Daftar Area')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">📍 Daftar Area</h1>
    <a href="{{ route('areas.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">+ Tambah Area</a>
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
                <th class="p-2 text-left">#</th>
                <th class="p-2 text-left">Nama Area</th>
                <th class="p-2 text-left">Jumlah Customer</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($areas as $area)
            <tr class="border-b">
                <td class="p-2">{{ $loop->iteration }}</td>
                <td class="p-2">{{ $area->name }}</td>
                <td class="p-2">{{ $area->customers()->count() }}</td>
                <td class="p-2">
                    <a href="{{ route('areas.edit', $area) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('areas.destroy', $area) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus area ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada area. Yuk tambahkan!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $areas->links() }}</div>
@endsection