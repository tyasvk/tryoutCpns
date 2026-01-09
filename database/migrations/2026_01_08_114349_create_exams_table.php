<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi ke Tryout (INI YANG SEBELUMNYA HILANG)
            $table->foreignId('tryout_id')->constrained()->onDelete('cascade');

            // Skor & Status
            $table->integer('score_twk')->default(0);
            $table->integer('score_tiu')->default(0);
            $table->integer('score_tkp')->default(0);
            $table->integer('total_score')->default(0);
            
            $table->enum('status', ['active', 'completed'])->default('active');
            
            // Data JSON untuk menyimpan jawaban user
            $table->json('answers')->nullable();
            
            // Fitur Anti-Cheat
            $table->integer('violations')->default(0);
            
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};