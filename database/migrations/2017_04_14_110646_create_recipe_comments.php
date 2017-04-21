<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_recipes',function(Blueprint $table){
            $table->integer('comment_id')->unsigned();
            $table->integer('recipe_id')->unsigned();
            
            $table->foreign("comment_id")->references('id')->on('comments');
            $table->foreign("recipe_id")->references('id')->on('recipes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_recipes');
    }
}
