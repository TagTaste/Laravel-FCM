<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecipeLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("recipe_likes",function(Blueprint $table){
            $table->integer('recipe_id')->unsigned();
            $table->integer('profile_id')->unsigned();
            
            $table->foreign('recipe_id')->references('id')->on('recipes');
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recipe_likes');
    }
}
