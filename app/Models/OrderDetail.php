<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price',
        'subtotal', // Tetap masukkan ini
        'notes'
    ];

    // Kita hapus fungsi booted() dan hitung manual di Controller saja
    // agar kamu lebih mudah memantau datanya.

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function menu(): BelongsTo {
        return $this->belongsTo(Menu::class);
    }
    
}
