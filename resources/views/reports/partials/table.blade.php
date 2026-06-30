<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Invoice</th>
                <th class="p-2 text-left">Tanggal</th>
                <th class="p-2 text-left">Customer</th>
                <th class="p-2 text-left">Produk</th>
                <th class="p-2 text-center">Jml</th>
                <th class="p-2 text-right">Total</th>
                <th class="p-2 text-left">Metode</th>
                <th class="p-2 text-left">Status</th>
                <th class="p-2 text-left">Kurir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-2 font-mono text-xs">ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td class="p-2 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td class="p-2">{{ $order->customer->name ?? '-' }}</td>
                <td class="p-2">{{ $order->product->name ?? '-' }}</td>
                <td class="p-2 text-center">{{ $order->quantity }}</td>
                <td class="p-2 text-right font-medium">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td class="p-2">{{ ucfirst($order->payment_method ?? 'cash') }}</td>
                <td class="p-2">
                    <span class="px-2 py-1 rounded text-xs 
                        @if($order->status == 'pending') bg-yellow-200 text-yellow-800
                        @elseif($order->status == 'processing') bg-blue-200 text-blue-800
                        @elseif($order->status == 'delivered') bg-green-200 text-green-800
                        @elseif($order->status == 'completed') bg-green-600 text-white
                        @else bg-red-200 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="p-2">{{ $order->courier->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>Tidak ada data yang ditemukan.</p>
                    <p class="text-sm mt-1">Ubah filter atau rentang tanggal.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($orders->total() > 0)
        <tfoot class="bg-gray-50 font-semibold">
            <tr>
                <td colspan="5" class="p-2 text-right">Total Order:</td>
                <td class="p-2 text-right text-blue-600">
                    Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}
                </td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

<div class="mt-4">
    {{ $orders->appends(request()->query())->links() }}
</div>