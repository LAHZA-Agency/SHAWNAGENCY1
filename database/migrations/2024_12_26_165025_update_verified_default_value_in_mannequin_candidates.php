<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->boolean('verified')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->boolean('verified')->default(false)->change();
        });
    }
};
