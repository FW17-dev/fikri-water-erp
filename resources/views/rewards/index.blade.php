@extends('layouts.app')
@section('title', 'Daftar Reward')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Daftar Reward</h1>
    <a href="{{ route('rewards.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah Reward</a>
    <a href="{{ route('rewards.claim') }}" class="bg-green-600 text-white px-4 py-2 rounded">Klaim Reward</a>
</div>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Nama</th>
                <th class="p-2 text-left">Poin</th>
                <th class="p-2 text-left">Stok</th>
                <th class="p-2 text-left">Status</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rewards as $reward)
            <tr>
                <td>{{ $reward->name }}</td>
                <td>{{ $reward->points_required }}</td>
                <td>{{ $reward->stock }}</td>
                <td>{{ $reward->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                <td>
                    <a href="{{ route('rewards.edit', $reward) }}" class="text-blue-600">Edit</a>
                    <form action="{{ route('rewards.destroy', $reward) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-4 text-center">Belum ada reward</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $rewards->links() }}</div>
@endsection