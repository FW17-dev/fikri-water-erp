@extends('layouts.app')
@section('title', 'Daftar Kurir')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Daftar Kurir</h1>
    <a href="{{ route('couriers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">+ Tambah Kurir</a>
</div>

<form method="GET" class="mb-4">
    <input type="text" name="search" placeholder="Cari nama kurir..." class="w-full md:w-1/3 px-4 py-2 border rounded-lg" value="{{ request('search') }}">
    <button type="submit" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded text-sm">Cari</button>
</form>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Nama</th>
                <th class="p-2 text-left">No. HP</th>
                <th class="p-2 text-left">Status</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($couriers as $courier)
            <tr class="border-b">
                <td class="p-2">{{ $courier->name }}</td>
                <td>{{ $courier->phone ?? '-' }}</td>
                <td>
                    <span class="px-2 py-1 rounded text-xs {{ $courier->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ $courier->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('couriers.edit', $courier) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('couriers.destroy', $courier) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kurir ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada kurir. Yuk tambahkan!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $couriers->links() }}</div>
@endsection