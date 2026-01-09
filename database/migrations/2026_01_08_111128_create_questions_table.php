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
        $table->foreignId('tryout_id')->constrained()->onDelete('cascade');
        
        // --- PERHATIKAN BARIS INI ---
        // Ganti 'question' menjadi 'content' agar sesuai dengan Controller
        $table->text('content'); 
        // ----------------------------

        $table->enum('type', ['TWK', 'TIU', 'TKP']);
        $table->text('option_a');
        $table->text('option_b');
        $table->text('option_c');
        $table->text('option_d');
        $table->text('option_e');
        
        // Poin TKP
        $table->integer('point_a')->default(0);
        $table->integer('point_b')->default(0);
        $table->integer('point_c')->default(0);
        $table->integer('point_d')->default(0);
        $table->integer('point_e')->default(0);
        
        $table->string('answer', 1)->nullable();
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