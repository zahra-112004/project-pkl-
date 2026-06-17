<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan',  'description' => 'Menu makanan utama'],
            ['name' => 'Minuman',  'description' => 'Menu minuman segar'],
            ['name' => 'Snack',    'description' => 'Menu camilan & gorengan'],
            ['name' => 'Dessert',  'description' => 'Menu penutup & es krim'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
