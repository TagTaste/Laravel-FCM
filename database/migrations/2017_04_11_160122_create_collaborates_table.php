<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaboratesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collaborates', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('i_am');
            $table->text('looking_for')->nullable();
            $table->text('purpose')->nullable();
            $table->text('deliverables')->nullable();
            $table->text('who_can_help')->nullable();
            $table->datetime('expires_on')->nullable();
            $table->integer('profile_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('company_id')->references('id')->on('companies');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('collaborates');
	}

}
