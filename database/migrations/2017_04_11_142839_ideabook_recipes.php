<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IdeabookRecipes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("ideabook_recipes",function(Blueprint $table){
            $table->integer("ideabook_id")->unsigned();
            $table->integer('recipe_id')->unsigned();
            $table->text('note')->nullable();
            
            $table->foreign('ideabook_id')->references('id')->on('ideabooks');
            $table->foreign('recipe_id')->references('id')->on('recipes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ideabook_recipes');
    }
}
