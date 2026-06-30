<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::orderBy('created_at', 'desc')->paginate(15);
        return view('backups.index', compact('backups'));
    }

    public function create()
    {
        // Backup database SQLite
        $sourcePath = database_path('database.sqlite');
        $backupName = 'backup-' . now()->format('Y-m-d_H-i-s') . '.sqlite';
        $backupPath = storage_path('app/backups/' . $backupName);

        // Buat folder jika belum ada
        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        // Copy file database
        if (File::exists($sourcePath)) {
            File::copy($sourcePath, $backupPath);

            // Simpan ke database
            $size = File::size($backupPath) / 1024; // KB
            Backup::create([
                'file_name' => $backupName,
                'file_path' => 'backups/' . $backupName,
                'size' => round($size, 2),
                'created_at' => now(),
            ]);

            // Catat log
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'backup',
                'model' => 'Backup',
                'description' => 'Backup database: ' . $backupName,
                'created_at' => now(),
            ]);

            return back()->with('success', 'Backup berhasil dibuat!');
        }

        return back()->with('error', 'File database tidak ditemukan!');
    }

    public function download($id)
    {
        $backup = Backup::findOrFail($id);
        $path = storage_path('app/' . $backup->file_path);

        if (File::exists($path)) {
            return response()->download($path, $backup->file_name);
        }

        return back()->with('error', 'File backup tidak ditemukan!');
    }

    public function destroy($id)
    {
        $backup = Backup::findOrFail($id);
        $path = storage_path('app/' . $backup->file_path);

        if (File::exists($path)) {
            File::delete($path);
        }

        $backup->delete();

        return back()->with('success', 'Backup berhasil dihapus!');
    }

    public function restore($id)
    {
        $backup = Backup::findOrFail($id);
        $backupPath = storage_path('app/' . $backup->file_path);

        if (!File::exists($backupPath)) {
            return back()->with('error', 'File backup tidak ditemukan!');
        }

        $dbPath = database_path('database.sqlite');

        // Backup database saat ini (sebelum restore)
        $currentBackup = 'pre-restore-' . now()->format('Y-m-d_H-i-s') . '.sqlite';
        if (File::exists($dbPath)) {
            File::copy($dbPath, storage_path('app/backups/' . $currentBackup));
        }

        // Restore
        File::copy($backupPath, $dbPath);

        // Catat log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'restore',
            'model' => 'Backup',
            'description' => 'Restore database dari: ' . $backup->file_name,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Database berhasil direstore!');
    }
}