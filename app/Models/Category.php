<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: 1 kategori punya banyak menu
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
