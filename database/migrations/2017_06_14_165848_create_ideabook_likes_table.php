<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdeabookLikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ideabook_likes', function(Blueprint $table) {
            $table->integer('profile_id')->unsigned();
            $table->integer('ideabook_id')->unsigned();

            $table->foreign('ideabook_id')->references('id')->on('ideabooks');
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
		Schema::drop('ideabook_likes');
	}

}
