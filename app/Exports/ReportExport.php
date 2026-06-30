<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $orders;
    protected $filters;

    public function __construct($orders, $filters = [])
    {
        $this->orders = $orders;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Tanggal',
            'Customer',
            'Produk',
            'Jumlah',
            'Total Harga',
            'Metode Bayar',
            'Status',
            'Kurir',
            'Catatan',
        ];
    }

    public function map($order): array
    {
        return [
            'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            $order->created_at->format('d/m/Y H:i'),
            $order->customer->name ?? '-',
            $order->product->name ?? '-',
            $order->quantity,
            'Rp ' . number_format($order->total_price, 0, ',', '.'),
            ucfirst($order->payment_method ?? 'cash'),
            ucfirst($order->status),
            $order->courier->name ?? '-',
            $order->notes ?? '-',
        ];
    }
}