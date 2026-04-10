<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWordpressPostIdToMannequinCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('wordpress_post_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mannequin_candidates', function (Blueprint $table) {
            $table->dropColumn('wordpress_post_id');
        });
    }
}