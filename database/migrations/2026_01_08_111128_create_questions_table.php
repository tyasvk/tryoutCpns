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
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        // Pastikan tryout_id ada jika Anda ingin relasi langsung di sini
        $table->foreignId('tryout_id')->constrained()->onDelete('cascade');
        $table->text('content');
        $table->enum('type', ['TWK', 'TIU', 'TKP']);
        $table->text('option_a');
        $table->text('option_b');
        $table->text('option_c');
        $table->text('option_d');
        $table->text('option_e');
        $table->string('answer', 1); // <--- KOLOM INI YANG HILANG
        $table->text('explanation')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
