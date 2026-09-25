<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pastikan akun administrator default ada
        \App\Models\User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'email' => 'admin@smanbenlutu.sch.id',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'level' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
