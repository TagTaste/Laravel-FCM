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
		Schema::create('recipe_articles', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dish_id')->unsigned()->nullable();
            $table->integer('step');
            $table->text('content');
            $table->integer('template_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("dish_id")->references("id")->on("dish_articles");
            $table->foreign("template_id")->references("id")->on("templates");
            $table->foreign("parent_id")->references("id")->on("recipe_articles");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recipe_articles');
	}

}
