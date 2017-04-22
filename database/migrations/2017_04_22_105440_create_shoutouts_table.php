<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoutoutsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shoutouts', function(Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->integer('profile_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('flag')->default(0);
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
		Schema::drop('shoutouts');
	}

}
