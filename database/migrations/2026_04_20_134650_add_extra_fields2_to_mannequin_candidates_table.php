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
            $table->string('finition_peau')->nullable()->after('tatouages');
            $table->string('sous_ton')->nullable()->after('finition_peau');
            $table->string('niveau')->nullable()->after('sous_ton');
            $table->string('emotions')->nullable()->after('niveau');
            $table->string('categorie')->nullable()->after('emotions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->dropColumn([
                'finition_peau',
                'sous_ton',
                'niveau',
                'emotions',
                'categorie'
            ]);
        });
    }
};
