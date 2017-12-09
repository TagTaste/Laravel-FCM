<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatLimitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chat_limits', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->tinyInteger('remaining')->unsigned();
            $table->tinyInteger('max')->unsigned()->nullable();
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chat_limits');
	}

}
