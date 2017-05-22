<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborationFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collaboration_fields', function(Blueprint $table) {
            $table->integer('collaboration_id')->unsigned();
            $table->integer('field_id')->unsigned();
            $table->foreign('collaboration_id')->references('id')->on('collaborates')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('collaboration_fields');
	}

}
