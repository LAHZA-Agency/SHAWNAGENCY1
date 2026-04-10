<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mannequin_measurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id'); 
            $table->unsignedBigInteger('user_id'); 

            // Measurement fields
            $table->float('head_circumference')->nullable();
            $table->float('neck_base_circumference')->nullable();
            $table->float('shoulder_length')->nullable();
            $table->float('arm_length')->nullable();
            $table->float('front_width')->nullable();
            $table->float('chest_circumference')->nullable();
            $table->float('waist_circumference')->nullable();
            $table->float('small_hips_circumference')->nullable();
            $table->float('hips_circumference')->nullable();
            $table->float('thigh_circumference')->nullable();
            $table->float('knee_circumference')->nullable();
            $table->float('calf_circumference')->nullable();
            $table->float('ankle_circumference')->nullable();
            $table->float('upper_arm_circumference')->nullable();
            $table->float('elbow')->nullable();
            $table->float('forearm_circumference')->nullable();
            $table->float('wrist_size')->nullable();
            $table->float('wrist_to_elbow')->nullable();
            $table->float('inseam_length')->nullable();
            $table->float('knee_height')->nullable();
            $table->float('side_height')->nullable();
            $table->float('total_height')->nullable();
            $table->timestamps();
            $table->foreign('candidate_id')->references('id')->on('mannequin_candidates')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mannequin_measurements');
    }
};
