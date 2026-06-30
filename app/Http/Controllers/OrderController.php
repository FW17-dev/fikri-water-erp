<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Courier;
use App\Models\Production;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'product', 'courier']);
        if ($request->search) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }
        $orders = $query->latest()->paginate(20);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();
        $couriers = Courier::where('is_active', true)->get();
        return view('orders.form', compact('customers', 'products', 'couriers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id'  => 'required|exists:products,id',
            'courier_id'  => 'nullable|exists:couriers,id',
            'quantity'    => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
            'notes'       => 'nullable|string',
        ]);

        $product = Product::find($validated['product_id']);
        $totalPrice = $product->price * $validated['quantity'];

        DB::transaction(function () use ($validated, $totalPrice, $product) {
            // 1. Buat order
            $order = Order::create([
                'customer_id'    => $validated['customer_id'],
                'product_id'     => $validated['product_id'],
                'courier_id'     => $validated['courier_id'],
                'quantity'       => $validated['quantity'],
                'total_price'    => $totalPrice,
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'status'         => 'pending',
                'notes'          => $validated['notes'] ?? null,
            ]);

            // 2. Tambah poin reward ke customer
            $customer = Customer::find($validated['customer_id']);
            $pointsEarned = $product->reward_points * $validated['quantity'];
            $customer->increment('points', $pointsEarned);
            $customer->update(['last_order_at' => now()]);

            // 3. Kurangi stok produksi (galon) - hanya jika produk adalah air minum galon
            //    asumsikan category 'Air Minum' atau 'Galon' yang mempengaruhi stok
            if (stripos($product->category, 'galon') !== false || stripos($product->name, 'galon') !== false) {
                // Ambil total produksi yang tersisa
                $totalProduction = Production::sum('quantity');
                $usedProduction = Order::whereIn('status', ['delivered', 'completed'])->sum('quantity');
                $remaining = $totalProduction - $usedProduction;

                // Jika stok tidak cukup, kita tetap buat order tapi kasih warning (bisa di-handle nanti)
                // Untuk sekarang, kita tidak mengurangi secara langsung, karena stok dihitung realtime dari produksi - order selesai
                // Tapi kita catat di log atau notifikasi nanti
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat! Poin customer bertambah.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,delivered,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        DB::transaction(function () use ($order, $newStatus, $oldStatus) {
            $order->update(['status' => $newStatus]);

            if ($newStatus == 'delivered' || $newStatus == 'completed') {
                // Jika order selesai, catat waktu pengiriman
                if ($newStatus == 'delivered' && !$order->delivered_at) {
                    $order->update(['delivered_at' => now()]);
                }

                // Jika sebelumnya belum dihitung stok (misal dari pending ke delivered)
                // kita tidak perlu mengurangi lagi, karena stok dihitung dari total production - total order completed/delivered
                // Namun kita bisa tambahkan logika jika mau mengurangi stok secara fisik di tabel productions (tidak disarankan)
                // Biarkan sistem menghitung realtime
            }

            // Jika status berubah ke cancelled, kita kembalikan poin customer (opsional)
            if ($newStatus == 'cancelled' && $oldStatus != 'cancelled') {
                $customer = $order->customer;
                $product = $order->product;
                $pointsTaken = $product->reward_points * $order->quantity;
                $customer->decrement('points', $pointsTaken);
            }
        });

        return back()->with('success', "Status order berubah menjadi: {$newStatus}");
    }

    // Tambahan method untuk melihat detail order jika diperlukan
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }
}