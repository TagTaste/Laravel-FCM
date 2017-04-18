<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayloadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('channel_payloads', function(Blueprint $table) {
            $table->increments('id');
            $table->string('channel_name');
            $table->json('payload');
            $table->timestamps();
            
            $table->foreign('channel_name')->references('name')->on("channels");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payloads');
	}

}
