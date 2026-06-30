@extends('layouts.app')
@section('title', 'Audit Log')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">📋 Audit Log</h1>
    <div class="flex gap-2">
        <form action="{{ route('audit-logs.clear-all') }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus semua log?')">
            @csrf @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">🗑️ Hapus Semua</button>
        </form>
    </div>
</div>

<!-- Filter -->
<form method="GET" class="bg-white p-4 rounded shadow mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Aksi</label>
            <select name="action" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                @foreach($actions as $action)
                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Model</label>
            <select name="model" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                @foreach($models as $model)
                <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>{{ $model }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
    </div>
    <div class="flex gap-2 mt-3">
        <input type="text" name="search" placeholder="Cari deskripsi..." value="{{ request('search') }}" class="px-3 py-2 border rounded-lg flex-1">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🔍 Filter</button>
        <a href="{{ route('audit-logs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Reset</a>
    </div>
</form>

<!-- Tabel -->
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Waktu</th>
                <th class="p-2 text-left">User</th>
                <th class="p-2 text-left">Aksi</th>
                <th class="p-2 text-left">Model</th>
                <th class="p-2 text-left">Deskripsi</th>
                <th class="p-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-2 text-xs">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td class="p-2">{{ $log->user->name ?? 'System' }}</td>
                <td class="p-2">
                    <span class="px-2 py-1 rounded text-xs 
                        @if($log->action == 'login') bg-green-200 text-green-800
                        @elseif($log->action == 'logout') bg-gray-200 text-gray-800
                        @elseif($log->action == 'create') bg-blue-200 text-blue-800
                        @elseif($log->action == 'update') bg-yellow-200 text-yellow-800
                        @elseif($log->action == 'delete') bg-red-200 text-red-800
                        @else bg-purple-200 text-purple-800
                        @endif">
                        {{ ucfirst($log->action) }}
                    </span>
                </td>
                <td class="p-2">{{ $log->model }}</td>
                <td class="p-2">{{ $log->description }}</td>
                <td class="p-2 text-center">
                    <form action="{{ route('audit-logs.destroy', $log->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus log ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-8 text-center text-gray-500">Belum ada log aktivitas</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $logs->appends(request()->query())->links() }}</div>
@endsection