<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // Filter berdasarkan action
        if ($request->action) {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan model
        if ($request->model) {
            $query->where('model', $request->model);
        }

        // Search di description
        if ($request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        // Filter tanggal
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Ambil daftar action & model untuk filter dropdown
        $actions = AuditLog::distinct()->pluck('action');
        $models = AuditLog::distinct()->pluck('model');

        return view('audit-logs.index', compact('logs', 'actions', 'models'));
    }

    public function destroy($id)
    {
        $log = AuditLog::findOrFail($id);
        $log->delete();

        return back()->with('success', 'Log berhasil dihapus!');
    }

    public function clearAll()
    {
        AuditLog::truncate();
        return back()->with('success', 'Semua log berhasil dibersihkan!');
    }
}