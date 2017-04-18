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
        //this table gets renamed later to recipes.
		Schema::create('dishes', function(Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->text('ingredients');
            $table->string('image')->nullable();
            $table->boolean('showcase')->default(0);
            $table->boolean('hasRecipe')->default(0);
            $table->string('category');
            $table->string('serving');
            $table->string('calorie');
            $table->string('time');
            $table->timestamps();
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dishes');
	}

}
