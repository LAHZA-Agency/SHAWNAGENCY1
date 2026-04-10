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
            $table->string('langues_parlees')->nullable();
            $table->string('couleur_cheveux')->nullable();
            $table->string('couleur_yeux')->nullable();
            $table->boolean('sport_pratique')->default(false);
            $table->boolean('piercings')->default(false);
            $table->boolean('tatouages')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->dropColumn('langues_parlees');
            $table->dropColumn('couleur_cheveux');
            $table->dropColumn('couleur_yeux');
            $table->dropColumn('sport_pratique');
            $table->dropColumn('piercings');
            $table->dropColumn('tatouages');
        });
    }
};
