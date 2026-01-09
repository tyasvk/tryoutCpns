<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // Kita ubah menjadi string agar tidak error lagi jika ada status baru di masa depan
            $table->string('status')->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // Jika ingin kembali ke enum (pastikan 'ongoing' disertakan)
            $table->enum('status', ['pending', 'ongoing', 'completed'])->default('pending')->change();
        });
    }
};