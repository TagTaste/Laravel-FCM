<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateOccupations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collaborate_occupations",function(Blueprint $table){
            $table->integer("collaborate_id")->unsigned();
            $table->integer("occupation_id")->unsigned();

            $table->foreign("collaborate_id")->references('id')->on('collaborates');
            $table->foreign("occupation_id")->references('id')->on('occupations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborate_occupations');
    }
}
