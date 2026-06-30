@extends('layouts.app')
@section('title', isset($financial) ? 'Edit Transaksi' : 'Tambah Transaksi')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ isset($financial) ? 'Edit' : 'Tambah' }} Transaksi Keuangan</h1>
    <form action="{{ isset($financial) ? route('financials.update', $financial) : route('financials.store') }}" method="POST">
        @csrf
        @if(isset($financial)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
            <select name="category" id="category" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ (old('category', $financial->category ?? '') == $cat) ? 'selected' : '' }}>
                    {{ ucfirst($cat) }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Sub Kategori</label>
            <select name="sub_category" id="sub_category" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Pilih Sub Kategori (Opsional)</option>
                @foreach($subCategories as $cat => $subs)
                    @foreach($subs as $sub)
                    <option value="{{ $sub }}" class="sub-option sub-{{ $cat }}" 
                        {{ (old('sub_category', $financial->sub_category ?? '') == $sub) ? 'selected' : '' }}
                        style="{{ old('category', $financial->category ?? '') != $cat ? 'display:none' : '' }}">
                        {{ $sub }}
                    </option>
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
            <input type="text" name="description" value="{{ old('description', $financial->description ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Transaksi</label>
            <select name="type" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="expense" {{ (old('type', $financial->type ?? '') == 'expense') ? 'selected' : '' }}>Pengeluaran</option>
                <option value="income" {{ (old('type', $financial->type ?? '') == 'income') ? 'selected' : '' }}>Pemasukan</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah (Rp)</label>
            <input type="number" name="amount" value="{{ old('amount', $financial->amount ?? '') }}" step="0.01" min="0" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Transaksi</label>
            <input type="date" name="transaction_date" value="{{ old('transaction_date', $financial->transaction_date ?? now()->format('Y-m-d')) }}" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Terhubung dengan Order (Opsional)</label>
            <select name="order_id" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Pilih Order</option>
                @foreach($orders as $order)
                <option value="{{ $order->id }}" {{ (old('order_id', $financial->order_id ?? '') == $order->id) ? 'selected' : '' }}>
                    #{{ $order->id }} - {{ $order->customer->name }} - Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category');
        const subSelect = document.getElementById('sub_category');
        
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            const options = subSelect.querySelectorAll('.sub-option');
            options.forEach(opt => {
                if (selectedCategory && opt.classList.contains('sub-' + selectedCategory)) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                }
            });
            // Reset pilihan jika tidak sesuai
            if (subSelect.value) {
                const currentVal = subSelect.value;
                const found = subSelect.querySelector(`option[value="${currentVal}"]`);
                if (found && found.style.display === 'none') {
                    subSelect.value = '';
                }
            }
        });
        // Trigger on load
        if (categorySelect.value) {
            categorySelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection