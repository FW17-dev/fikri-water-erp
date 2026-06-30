@extends('layouts.app')
@section('title', 'Laporan Order')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">📊 Laporan Order</h1>
    <button onclick="document.getElementById('exportForm').submit()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
        📥 Export Excel
    </button>
</div>

<!-- Form Filter -->
<form method="GET" action="{{ route('reports.index') }}" id="reportForm" class="bg-white p-4 rounded shadow mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Tanggal (wajib) -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Tanggal Mulai *</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Tanggal Akhir *</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border rounded-lg" required>
        </div>

        <!-- Customer -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Customer</label>
            <select name="customer_id" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Produk -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Produk</label>
            <select name="product_id" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Kurir -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Kurir</label>
            <select name="courier_id" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                @foreach($couriers as $courier)
                <option value="{{ $courier->id }}" {{ request('courier_id') == $courier->id ? 'selected' : '' }}>
                    {{ $courier->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Supplier -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Supplier</label>
            <select name="supplier_id" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Area -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Area</label>
            <select name="area_id" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <!-- Metode Pembayaran -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Metode Bayar</label>
            <select name="payment_method" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="debt" {{ request('payment_method') == 'debt' ? 'selected' : '' }}>Piutang</option>
            </select>
        </div>

        <!-- Hutang -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Filter Hutang</label>
            <select name="debt_filter" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                <option value="with_debt" {{ request('debt_filter') == 'with_debt' ? 'selected' : '' }}>Dengan Hutang</option>
                <option value="no_debt" {{ request('debt_filter') == 'no_debt' ? 'selected' : '' }}>Tanpa Hutang</option>
            </select>
        </div>

        <!-- Reward -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Filter Reward</label>
            <select name="reward_filter" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Semua</option>
                <option value="with_reward" {{ request('reward_filter') == 'with_reward' ? 'selected' : '' }}>Ada Reward</option>
            </select>
        </div>

        <!-- Search -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-1">Cari (Invoice/Customer)</label>
            <input type="text" name="search" placeholder="Ketik keyword..." value="{{ request('search') }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-2 mt-4">
        <div class="flex flex-wrap gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">🔍 Tampilkan</button>
            <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Reset</a>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <label class="text-gray-700 text-sm">Simpan Template:</label>
            <input type="text" id="templateName" placeholder="Nama template" class="px-3 py-2 border rounded-lg text-sm w-40">
            <button type="button" onclick="saveTemplate()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 text-sm">💾 Simpan</button>
        </div>
    </div>
</form>

<!-- Template yang tersimpan -->
@if($templates->isNotEmpty())
<div class="bg-white p-4 rounded shadow mb-4">
    <h3 class="font-semibold text-sm mb-2">📁 Template Tersimpan:</h3>
    <div class="flex flex-wrap gap-2">
        @foreach($templates as $template)
        <form method="GET" action="{{ route('reports.index') }}" class="inline">
            <input type="hidden" name="template_id" value="{{ $template->id }}">
            <button type="submit" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-sm">
                {{ $template->name }}
            </button>
        </form>
        <form action="{{ route('reports.template.delete', $template->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus template ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">✕</button>
        </form>
        @endforeach
    </div>
</div>
@endif

<!-- Form Export (hidden) -->
<form method="POST" action="{{ route('reports.export') }}" id="exportForm">
    @csrf
    @foreach(request()->all() as $key => $value)
        @if(!in_array($key, ['_token', 'page']))
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
</form>

<!-- Tabel Hasil -->
<div id="reportTable">
    @include('reports.partials.table', ['orders' => $orders])
</div>

@push('scripts')
<script>
function saveTemplate() {
    const name = document.getElementById('templateName').value.trim();
    if (!name) {
        alert('Masukkan nama template!');
        return;
    }

    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    const filters = {};
    for (let [key, value] of formData.entries()) {
        if (value && !key.startsWith('_')) {
            filters[key] = value;
        }
    }

    fetch('{{ route("reports.template.save") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ name: name, filters: filters })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Gagal menyimpan template.');
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error);
    });
}
</script>
@endpush
@endsection