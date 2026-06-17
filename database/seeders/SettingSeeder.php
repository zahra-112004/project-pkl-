<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'restaurant_name',    'value' => 'QResto'],
            ['key' => 'restaurant_address', 'value' => 'Jl. Contoh No. 1, Kota'],
            ['key' => 'restaurant_phone',   'value' => '08123456789'],
            ['key' => 'restaurant_footer',  'value' => 'Terima kasih telah berkunjung di QResto!'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
