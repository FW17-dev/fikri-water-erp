<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\SupplierController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resource('customers', CustomerController::class);
    Route::resource('areas', AreaController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('couriers', CourierController::class);
    Route::resource('orders', OrderController::class);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    Route::resource('productions', ProductionController::class);
    Route::resource('rewards', RewardController::class);
    Route::get('rewards/claim', [RewardController::class, 'claimForm'])->name('rewards.claim');
    Route::post('rewards/claim', [RewardController::class, 'claim'])->name('rewards.processClaim');

    Route::resource('financials', FinancialController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::post('/reports/template', [ReportController::class, 'saveTemplate'])->name('reports.template.save');
    Route::get('/reports/template/load', [ReportController::class, 'loadTemplate'])->name('reports.template.load');

    // Audit Log
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::delete('/audit-logs/{id}', [AuditLogController::class, 'destroy'])->name('audit-logs.destroy');
    Route::delete('/audit-logs/clear-all', [AuditLogController::class, 'clearAll'])->name('audit-logs.clear-all');

    // Backup
    Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups', [BackupController::class, 'create'])->name('backups.store');
    Route::get('/backups/{id}/download', [BackupController::class, 'download'])->name('backups.download');
    Route::delete('/backups/{id}', [BackupController::class, 'destroy'])->name('backups.destroy');
    Route::post('/backups/{id}/restore', [BackupController::class, 'restore'])->name('backups.restore');


});

// Endpoint setup database untuk deploy Vercel (jalankan migrate + seed sekali saja)
// Akses: https://domain-anda.vercel.app/setup-database?key=SETUP_SECRET_KEY
Route::get('/setup-database', function (\Illuminate\Http\Request $request) {
    if ($request->query('key') !== env('SETUP_SECRET_KEY')) {
        abort(403, 'Forbidden');
    }

    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
    $seedOutput = \Illuminate\Support\Facades\Artisan::output();

    return response()->json([
        'status' => 'ok',
        'migrate' => $migrateOutput,
        'seed' => $seedOutput,
    ]);
});