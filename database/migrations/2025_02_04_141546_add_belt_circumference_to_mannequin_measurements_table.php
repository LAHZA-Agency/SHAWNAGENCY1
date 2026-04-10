<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mannequin_measurements', function (Blueprint $table) {
            $table->float('belt_circumference')->nullable();
        });
    }

    public function down()
    {
        Schema::table('mannequin_measurements', function (Blueprint $table) {
            $table->dropColumn('belt_circumference');
        });
    }
};
