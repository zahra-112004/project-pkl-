<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; 
use App\Models\Table;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    for ($i = 1; $i <= 10; $i++) {
        \App\Models\Table::create([
            'number' => $i,
            'qr_token' => Str::random(32),
            'capacity' => 4,
            'status' => 'available'
        ]);
    }
}
}
