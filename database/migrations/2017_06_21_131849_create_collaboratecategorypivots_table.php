<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateCategoryPivotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collaborate_category_pivots', function(Blueprint $table) {
            $table->integer('collaborate_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->foreign('category_id')->references('id')->on('collaborate_categories')->onDelete('cascade');
            $table->foreign("collaborate_id")->references('id')->on('collaborates')->onDelete('cascade');


        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('collaborate_category_pivots');
	}

}
