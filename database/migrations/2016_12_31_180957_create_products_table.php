<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateproductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->float('price');
            $table->text('image');
            $table->float('moq');
            $table->string('type');
            $table->string('about');
            $table->string('ingredients');
            $table->string('certifications');
            $table->string('portion_size');
            $table->string('shelf_life');
            $table->string('mode');
            $table->integer('user_id')->unsigned();
            $table->integer('profile_type_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('profile_type_id')->references('id')->on('profile_types');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
