<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->string('instagram_link')->nullable();
            $table->enum('model_type', ['Model', 'Mannequin'])->default('Mannequin');
        });
    }

    public function down()
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->dropColumn('instagram_link');
            $table->dropColumn('model_type');
        });
    }
};
