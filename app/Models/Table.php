<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'number',
        'qr_token',
        'capacity',
        'status'
    ];

    // Relasi: 1 meja punya banyak orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Ambil pesanan aktif di meja ini
    public function activeOrder()
    {
        return $this->hasOne(Order::class)
                    ->whereIn('status', ['pending', 'paid', 'cooking', 'ready']);
    }
}
