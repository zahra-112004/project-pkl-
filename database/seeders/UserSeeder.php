<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Abel Admin',
            'email'     => 'abel@qresto.com',
            'password'  => Hash::make('12345678'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Zahra kasir',
            'email'     => 'zahra@qresto.com',
            'password'  => Hash::make('12345678'),
            'role'      => 'kasir',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Ara Dapur',
            'email'     => 'ara@qresto.com',
            'password'  => Hash::make('12345678'),
            'role'      => 'dapur',
            'is_active' => true,
        ]);
    }
}
