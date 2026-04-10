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
            $table->date('disponibilite_debut')->nullable();
            $table->date('disponibilite_fin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->dropColumn(['disponibilite_debut', 'disponibilite_fin']);
        });
    }
};
