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
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->string('sport_pratique', 255)->nullable()->change();
            $table->string('piercings', 255)->nullable()->change();
            $table->string('tatouages', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->boolean('sport_pratique')->nullable()->change();
            $table->boolean('piercings')->nullable()->change();
            $table->boolean('tatouages')->nullable()->change();
        });
    }
};
