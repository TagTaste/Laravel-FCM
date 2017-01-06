<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDishArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dish_articles', function(Blueprint $table) {
            $table->increments('id');
            $table->boolean('showcase')->default(0);
            $table->boolean('hasRecipe')->default(0);
            $table->integer('article_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("article_id")->references("id")->on("articles");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dish_articles');
	}

}
