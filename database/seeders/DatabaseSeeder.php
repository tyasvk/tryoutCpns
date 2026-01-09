<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ---------------------------------------------------
        // 1. AKUN ADMIN (Untuk Kelola Soal)
        // ---------------------------------------------------
        User::firstOrCreate(
            ['email' => 'admin@tryout.com'], // Kunci pengecekan (agar tidak duplikat)
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Password default
                'is_admin' => true, // Menandakan dia admin
                'email_verified_at' => now(),
            ]
        );

        // ---------------------------------------------------
        // 2. AKUN PESERTA (Untuk Testing Ujian)
        // ---------------------------------------------------
        User::firstOrCreate(
            ['email' => 'peserta@tryout.com'],
            [
                'name' => 'Peserta CPNS',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}