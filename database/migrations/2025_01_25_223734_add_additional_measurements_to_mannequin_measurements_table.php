<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mannequin_measurements', function (Blueprint $table) {
            $table->float('pointure')->nullable();
            $table->string('confection')->nullable();
            $table->float('poids')->nullable();
            $table->float('tour_de_hanches')->nullable();
        });
    }

    public function down()
    {
        Schema::table('mannequin_measurements', function (Blueprint $table) {
            $table->dropColumn('pointure');
            $table->dropColumn('confection');
            $table->dropColumn('poids');
            $table->dropColumn('tour_de_hanches');
        });
    }
};
