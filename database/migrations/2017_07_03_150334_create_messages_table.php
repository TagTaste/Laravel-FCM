<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chat_messages', function(Blueprint $table) {
            $table->increments('id');
            $table->string('message')->nullable();
            $table->integer('chat_id')->unsigned();
            $table->integer('profile_id')->unsigned();
            $table->timestamp('read_on')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('profile_id')->references('id')->on('profiles')->onDeletes('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chat_messages');
	}

}
