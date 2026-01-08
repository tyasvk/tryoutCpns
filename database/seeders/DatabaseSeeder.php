<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan firstOrCreate agar jika email sudah ada, sistem tidak akan error
        User::firstOrCreate(
            ['email' => 'admin@example.com'], // Cek berdasarkan email
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Hapus baris ini jika menyebabkan konflik dengan "test@example.com" bawaan Laravel
        // User::factory(10)->create(); 
    }
}