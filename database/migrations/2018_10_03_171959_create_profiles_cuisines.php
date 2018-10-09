<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesCuisines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("profiles_cuisines",function(Blueprint $table){
            $table->integer("profile_id")->unsigned();
            $table->integer("cuisine_id")->unsigned();

            $table->foreign("profile_id")->references('id')->on('profiles');
            $table->foreign("cuisine_id")->references('id')->on('cuisines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profiles_cuisines');
    }
}
