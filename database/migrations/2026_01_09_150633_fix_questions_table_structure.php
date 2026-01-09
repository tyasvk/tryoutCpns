<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Menambahkan kolom yang mungkin belum ada
            if (!Schema::hasColumn('questions', 'correct_answer')) {
                $table->char('correct_answer', 1)->nullable()->after('option_e');
            }
            if (!Schema::hasColumn('questions', 'explanation')) {
                $table->text('explanation')->nullable()->after('correct_answer');
            }
            
            // Kolom Poin untuk TKP (Pembobotan)
            foreach (['a', 'b', 'c', 'd', 'e'] as $opt) {
                $column = "points_$opt";
                if (!Schema::hasColumn('questions', $column)) {
                    $table->integer($column)->default(0)->after('explanation');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['correct_answer', 'explanation', 'points_a', 'points_b', 'points_c', 'points_d', 'points_e']);
        });
    }
};