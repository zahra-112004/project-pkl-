<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
    'table_id',
    'kasir_id',
    'order_code',
    'customer_name',
    'status',           // Tetap ada untuk Dapur
    'payment_status',   // BARU
    'payment_method',   // BARU
    'total_price',
    'payment_amount',   // BARU
    'change_amount',    // BARU
    'notes',
    'paid_at',
    'served_at'
];

    protected $casts = [
        'total_price' => 'decimal:2',
        'paid_at'     => 'datetime',
        'served_at'   => 'datetime',
    ];

    /**
     * Relasi: Order diproses oleh 1 Kasir
     */
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    /**
     * Relasi: Order milik 1 meja
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Relasi: 1 Order punya banyak detail (OrderDetail)
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Generate kode order otomatis: QRS-2026-001
     * Fungsi ini bisa dipanggil di Controller saat proses Checkout
     */
    public static function generateOrderCode(): string
    {
        $year = date('Y');
        $last = self::whereYear('created_at', $year)->count() + 1;
        return 'QRS-' . date('YmdHis') . '-' . rand(1000, 9999);
    }
}
