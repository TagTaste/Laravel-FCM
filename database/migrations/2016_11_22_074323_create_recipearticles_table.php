<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recipes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dish_id')->unsigned()->nullable();
            $table->integer('step');
            $table->text('content');
            $table->integer('template_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('difficulty_level')->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("dish_id")->references("id")->on("dishes");
            $table->foreign("template_id")->references("id")->on("templates");
            $table->foreign("parent_id")->references("id")->on("recipes");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recipes');
	}

}
