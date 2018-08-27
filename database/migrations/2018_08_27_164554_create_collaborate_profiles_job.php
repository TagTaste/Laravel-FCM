<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateProfilesJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collaborate_profiles_job",function(Blueprint $table){
            $table->integer("collaborate_id")->unsigned();
            $table->integer("job_id")->unsigned();

            $table->foreign("collaborate_id")->references('id')->on('collaborates');
            $table->foreign("job_id")->references('id')->on('profiles_job');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborate_profiles_job');
    }
}
