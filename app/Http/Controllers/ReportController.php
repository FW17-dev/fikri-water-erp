<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Courier;
use App\Models\Supplier;
use App\Models\Area;
use App\Models\ReportTemplate;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data untuk filter dropdown
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $couriers = Courier::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $areas = Area::orderBy('name')->get();

        // Ambil template yang disimpan
        $templates = ReportTemplate::where('user_id', auth()->id())->get();

        // Jika ada parameter template, load filter-nya
        if ($request->template_id) {
            $template = ReportTemplate::find($request->template_id);
            if ($template) {
                $request->merge($template->filters);
            }
        }

        // Query orders dengan relasi
        $query = Order::with(['customer', 'product', 'courier']);

        // Filter tanggal (wajib)
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter opsional
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->courier_id) {
            $query->where('courier_id', $request->courier_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function ($q2) use ($request) {
                      $q2->where('name', 'like', "%{$request->search}%")
                         ->orWhere('phone', 'like', "%{$request->search}%");
                  })
                  ->orWhereHas('product', function ($q2) use ($request) {
                      $q2->where('name', 'like', "%{$request->search}%");
                  });
            });
        }
        if ($request->area_id) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('area_id', $request->area_id);
            });
        }
        if ($request->supplier_id) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }
        if ($request->debt_filter == 'with_debt') {
            $query->whereHas('customer', function ($q) {
                $q->where('debt', '>', 0);
            });
        } elseif ($request->debt_filter == 'no_debt') {
            $query->whereHas('customer', function ($q) {
                $q->where('debt', '=', 0);
            });
        }
        if ($request->reward_filter == 'with_reward') {
            $query->whereHas('customer', function ($q) {
                $q->where('points', '>', 0);
            });
        }

        // Eksekusi query
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('reports.index', compact(
            'orders',
            'customers',
            'products',
            'couriers',
            'suppliers',
            'areas',
            'templates'
        ));
    }

    public function export(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Query sama seperti di index
        $query = Order::with(['customer', 'product', 'courier']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter lainnya
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->courier_id) {
            $query->where('courier_id', $request->courier_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function ($q2) use ($request) {
                      $q2->where('name', 'like', "%{$request->search}%");
                  });
            });
        }
        if ($request->area_id) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('area_id', $request->area_id);
            });
        }
        if ($request->supplier_id) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diexport.');
        }

        $filters = $request->all();
        return Excel::download(new ReportExport($orders, $filters), 'laporan-order-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function saveTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'filters' => 'required|array',
        ]);

        $template = ReportTemplate::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'filters' => $request->filters,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template berhasil disimpan!',
            'template' => $template
        ]);
    }

    public function deleteTemplate($id)
    {
        $template = ReportTemplate::where('user_id', auth()->id())->findOrFail($id);
        $template->delete();

        return back()->with('success', 'Template berhasil dihapus!');
    }
}