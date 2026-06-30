@extends('layouts.app')
@section('title', 'Backup Database')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">💾 Backup Database</h1>
    <form action="{{ route('backups.store') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">➕ Buat Backup Baru</button>
    </form>
</div>

<!-- Info -->
<div class="bg-blue-50 border border-blue-200 p-4 rounded mb-4 text-sm">
    <p>💡 Backup akan menyimpan file database SQLite ke folder <code>storage/app/backups/</code></p>
</div>

<!-- Tabel -->
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">#</th>
                <th class="p-2 text-left">Nama File</th>
                <th class="p-2 text-left">Ukuran</th>
                <th class="p-2 text-left">Tanggal</th>
                <th class="p-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($backups as $backup)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-2">{{ $loop->iteration }}</td>
                <td class="p-2">{{ $backup->file_name }}</td>
                <td class="p-2">{{ $backup->size }} KB</td>
                <td class="p-2">{{ $backup->created_at->format('d/m/Y H:i') }}</td>
                <td class="p-2 text-center">
                    <a href="{{ route('backups.download', $backup->id) }}" class="text-blue-600 hover:underline text-xs mr-2">⬇️ Download</a>
                    <form action="{{ route('backups.restore', $backup->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin restore database ini? Data saat ini akan di-overwrite!')">
                        @csrf
                        <button type="submit" class="text-green-600 hover:text-green-800 text-xs mr-2">🔄 Restore</button>
                    </form>
                    <form action="{{ route('backups.destroy', $backup->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus backup ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs">🗑️</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-8 text-center text-gray-500">Belum ada backup</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $backups->links() }}</div>
@endsection