<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'sub_category',
        'description',
        'type',
        'amount',
        'transaction_date',
        'order_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relasi ke order (jika transaksi terkait order)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scope untuk memisahkan income/expense
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }
}