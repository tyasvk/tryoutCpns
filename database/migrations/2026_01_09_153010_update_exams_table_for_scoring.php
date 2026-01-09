<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // Cek satu per satu sebelum menambah kolom
            if (!Schema::hasColumn('exams', 'score_twk')) {
                $table->integer('score_twk')->default(0);
            }
            if (!Schema::hasColumn('exams', 'score_tiu')) {
                $table->integer('score_tiu')->default(0);
            }
            if (!Schema::hasColumn('exams', 'score_tkp')) {
                $table->integer('score_tkp')->default(0);
            }
            if (!Schema::hasColumn('exams', 'total_score')) {
                $table->integer('total_score')->default(0);
            }
            if (!Schema::hasColumn('exams', 'is_passed')) {
                $table->boolean('is_passed')->default(false);
            }
            if (!Schema::hasColumn('exams', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['score_twk', 'score_tiu', 'score_tkp', 'total_score', 'is_passed', 'completed_at']);
        });
    }
};