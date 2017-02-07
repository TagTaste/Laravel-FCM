<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfessionalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('professionals', function(Blueprint $table) {
            $table->increments('id');
            $table->text('ingredients')->nullable();
            $table->text('favourite_moments')->nullable();
            $table->text('famous_recipes')->nullable();
            $table->integer('profile_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

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
		Schema::drop('professionals');
	}

}
