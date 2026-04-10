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
        Schema::create('model_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('mannequin_candidates')->onDelete('cascade');
            $table->integer('rating')->unsigned()->min(0)->max(20);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_ratings');
    }
};
