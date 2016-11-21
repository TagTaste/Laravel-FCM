<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('followers', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('chef_id')->unsigned();
            $table->integer('follower_id')->unsigned();
            $table->timestamps();

            $table->foreign('chef_id')->references('id')->on('users');
            $table->foreign('follower_id')->references('id')->on('users');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('followers');
	}

}
