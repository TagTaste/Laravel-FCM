<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateProfilesSpecialization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collaborate_profiles_specialization",function(Blueprint $table){
            $table->integer("collaborate_id")->unsigned();
            $table->integer("specialization_id")->unsigned();

            $table->foreign("collaborate_id")->references('id')->on('collaborates');
            $table->foreign("specialization_id")->references('id')->on('profiles_specialization');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborate_profiles_specialization');
    }
}
