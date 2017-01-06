<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('templates', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('view');
            $table->boolean('enabled')->default(0);
            $table->integer('template_type_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('template_type_id')->references('id')->on('template_types');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('templates');
	}

}
