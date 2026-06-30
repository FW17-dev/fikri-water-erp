<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
   public function index(Request $request)
{
    $query = Financial::query();

    // Filter
    if ($request->category) {
        $query->where('category', $request->category);
    }
    if ($request->type) {
        $query->where('type', $request->type);
    }
    if ($request->start_date) {
        $query->whereDate('transaction_date', '>=', $request->start_date);
    }
    if ($request->end_date) {
        $query->whereDate('transaction_date', '<=', $request->end_date);
    }
    if ($request->search) {
        $query->where('description', 'like', "%{$request->search}%");
    }

    $financials = $query->orderBy('transaction_date', 'desc')->paginate(20);

    // Ringkasan
    $totalIncome = Financial::income()->sum('amount');
    $totalExpense = Financial::expense()->sum('amount');
    $balance = $totalIncome - $totalExpense;

    // Per kategori
    $categories = ['operasional', 'transfer', 'keluarga', 'pribadi'];
    $categoryData = [];
    foreach ($categories as $cat) {
        $categoryData[$cat] = [
            'income'  => Financial::where('category', $cat)->income()->sum('amount'),
            'expense' => Financial::where('category', $cat)->expense()->sum('amount'),
        ];
    }

    // 7 hari terakhir (gunakan try-catch untuk aman)
    try {
        $dailyData = Financial::where('transaction_date', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(transaction_date) as date, type, SUM(amount) as total'))
            ->groupBy('date', 'type')
            ->orderBy('date', 'asc')
            ->get();
    } catch (\Exception $e) {
        $dailyData = collect([]);
    }

    // Data untuk filter dropdown
    $subCategories = [
        'operasional' => ['Pembelian air', 'Tutup galon', 'Tissue', 'Filter', 'BBM', 'Service motor', 'Listrik', 'Kuota'],
        'transfer'    => ['Tabungan Rp75.000/hari'],
        'keluarga'    => ['Makan pegawai', 'Gaji adik'],
        'pribadi'     => ['Kuliah', 'Bensin', 'Jajan'],
    ];

    return view('financials.index', compact(
        'financials',
        'totalIncome',
        'totalExpense',
        'balance',
        'categoryData',
        'dailyData',
        'categories',
        'subCategories'
    ));
}

    public function create()
    {
        $categories = ['operasional', 'transfer', 'keluarga', 'pribadi'];
        $subCategories = [
            'operasional' => ['Pembelian air', 'Tutup galon', 'Tissue', 'Filter', 'BBM', 'Service motor', 'Listrik', 'Kuota'],
            'transfer'    => ['Tabungan Rp75.000/hari'],
            'keluarga'    => ['Makan pegawai', 'Gaji adik'],
            'pribadi'     => ['Kuliah', 'Bensin', 'Jajan'],
        ];
        $orders = Order::whereIn('status', ['completed', 'delivered'])->get();

        return view('financials.form', compact('categories', 'subCategories', 'orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'         => 'required|string|in:operasional,transfer,keluarga,pribadi',
            'sub_category'     => 'nullable|string',
            'description'      => 'required|string|max:255',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'order_id'         => 'nullable|exists:orders,id',
        ]);

        Financial::create($validated);

        return redirect()->route('financials.index')
            ->with('success', 'Transaksi keuangan berhasil ditambahkan!');
    }

    public function edit(Financial $financial)
    {
        $categories = ['operasional', 'transfer', 'keluarga', 'pribadi'];
        $subCategories = [
            'operasional' => ['Pembelian air', 'Tutup galon', 'Tissue', 'Filter', 'BBM', 'Service motor', 'Listrik', 'Kuota'],
            'transfer'    => ['Tabungan Rp75.000/hari'],
            'keluarga'    => ['Makan pegawai', 'Gaji adik'],
            'pribadi'     => ['Kuliah', 'Bensin', 'Jajan'],
        ];
        $orders = Order::whereIn('status', ['completed', 'delivered'])->get();

        return view('financials.form', compact('financial', 'categories', 'subCategories', 'orders'));
    }

    public function update(Request $request, Financial $financial)
    {
        $validated = $request->validate([
            'category'         => 'required|string|in:operasional,transfer,keluarga,pribadi',
            'sub_category'     => 'nullable|string',
            'description'      => 'required|string|max:255',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'order_id'         => 'nullable|exists:orders,id',
        ]);

        $financial->update($validated);

        return redirect()->route('financials.index')
            ->with('success', 'Transaksi keuangan berhasil diperbarui!');
    }

    public function destroy(Financial $financial)
    {
        $financial->delete();
        return back()->with('success', 'Transaksi keuangan dihapus!');
    }

    // Dashboard keuangan (opsional, bisa diakses dari menu)
    public function dashboard()
    {
        $totalIncome = Financial::income()->sum('amount');
        $totalExpense = Financial::expense()->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Per kategori
        $categories = ['operasional', 'transfer', 'keluarga', 'pribadi'];
        $categoryData = [];
        foreach ($categories as $cat) {
            $categoryData[$cat] = [
                'income'  => Financial::where('category', $cat)->income()->sum('amount'),
                'expense' => Financial::where('category', $cat)->expense()->sum('amount'),
            ];
        }

        // 7 hari terakhir
        $dailyData = Financial::where('transaction_date', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(transaction_date) as date, type, SUM(amount) as total'))
            ->groupBy('date', 'type')
            ->orderBy('date', 'asc')
            ->get();

        return view('financials.dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'categoryData',
            'dailyData'
        ));
    }
}