<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Production;
use App\Models\Financial;
use App\Models\Reward;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $totalCustomers = Customer::count();
        $totalDebt = Customer::sum('debt');

        // Produksi air
        $totalProduction = Production::sum('quantity');
        $usedProduction = Order::whereIn('status', ['delivered', 'completed'])->sum('quantity');
        $remainingProduction = $totalProduction - $usedProduction;

        // Keuangan (kas masuk / keluar)
        $totalIncome = Financial::where('type', 'income')->sum('amount');
        $totalExpense = Financial::where('type', 'expense')->sum('amount');
        $cashBalance = $totalIncome - $totalExpense;

        // Reward terpopuler
        $popularRewards = Reward::withCount('claims')
            ->orderBy('claims_count', 'desc')
            ->limit(5)
            ->get();

        // Order terakhir
        $recentOrders = Order::with(['customer', 'product', 'courier'])
            ->latest()
            ->limit(10)
            ->get();

        // Ringkasan per hari (untuk grafik sederhana, 7 hari terakhir)
        try {
    $dailyRevenue = Order::where('status', 'completed')
        ->where('created_at', '>=', now()->subDays(7))
        ->select(DB::raw('DATE(created_at) as date, SUM(total_price) as total'))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
} catch (\Exception $e) {
    // Jika kolom belum ada, berikan data kosong
    $dailyRevenue = collect([]);
}

        return view('dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalCustomers',
            'totalDebt',
            'remainingProduction',
            'cashBalance',
            'popularRewards',
            'recentOrders',
            'dailyRevenue'
        ));
    }
}