<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileOccupations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("profile_occupations",function(Blueprint $table){
            $table->integer("profile_id")->unsigned();
            $table->integer("occupation_id")->unsigned();

            $table->foreign("profile_id")->references('id')->on('profiles');
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
        Schema::drop('profile_occupations');
    }
}
