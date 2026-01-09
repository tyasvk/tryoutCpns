<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom subject.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Menempatkan kolom subject setelah tryout_id agar struktur DB tetap rapi
            // Kolom ini akan menyimpan kategori: TWK, TIU, atau TKP
            if (!Schema::hasColumn('questions', 'subject')) {
                $table->string('subject')->after('tryout_id')->nullable()->comment('Kategori: TWK, TIU, atau TKP');
            }
        });
    }

    /**
     * Batalkan migrasi (Rollback).
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'subject')) {
                $table->dropColumn('subject');
            }
        });
    }
};