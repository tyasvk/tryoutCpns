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
        // Tipe: free, paid, grand_to
        if (!Schema::hasColumn('tryouts', 'type')) {
            $table->string('type')->default('free')->after('name');
        }
        if (!Schema::hasColumn('tryouts', 'price')) {
            $table->integer('price')->default(0)->after('type');
        }
        if (!Schema::hasColumn('tryouts', 'start_at')) {
            $table->dateTime('start_at')->nullable()->after('price');
        }
        if (!Schema::hasColumn('tryouts', 'end_at')) {
            $table->dateTime('end_at')->nullable()->after('start_at');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
