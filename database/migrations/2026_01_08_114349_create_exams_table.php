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
    Schema::create('exams', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->integer('score_twk')->default(0);
        $table->integer('score_tiu')->default(0);
        $table->integer('score_tkp')->default(0);
        $table->integer('total_score')->default(0);
        $table->timestamp('started_at');
        $table->timestamp('completed_at')->nullable();
        $table->string('status')->default('in_progress'); // in_progress, completed
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
