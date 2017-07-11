<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelSubscribersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('model_subscribers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('model');
            $table->integer('model_id')->unsigned();
            $table->integer('profile_id')->unsigned();
            $table->timestamp('muted_on')->nullable();
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
		Schema::drop('model_subscribers');
	}

}
