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
        Schema::create('mannequin_candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('profile')->nullable();
            $table->enum('status_model', ['pending', 'approved', 'rejected']);
            $table->boolean('verified')->default(0);
            $table->string('tel')->nullable();
            $table->string('identity_document')->nullable();
            $table->enum('gender_identity', ['Femme', 'Homme']);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mannequin_candidates');
    }
};
