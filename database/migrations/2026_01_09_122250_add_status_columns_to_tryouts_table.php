<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('tryouts', function (Blueprint $table) {
        // Menambahkan kolom is_published dan type (agar fitur kategori juga jalan)
        if (!Schema::hasColumn('tryouts', 'is_published')) {
            $table->boolean('is_published')->default(false)->after('duration');
        }
        if (!Schema::hasColumn('tryouts', 'type')) {
            $table->string('type')->default('free')->after('is_published');
        }
    });
}

public function down(): void
{
    Schema::table('tryouts', function (Blueprint $table) {
        $table->dropColumn(['is_published', 'type']);
    });
}
};
