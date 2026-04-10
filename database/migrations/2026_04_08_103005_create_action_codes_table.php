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
        Schema::create('action_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6);
            $table->string('action');          
            $table->json('data')->nullable();   
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_codes');
    }
};
