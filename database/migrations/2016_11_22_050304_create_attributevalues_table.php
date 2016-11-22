<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attribute_values', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('value');
            $table->boolean('default')->default("0");
            $table->integer('attribute_id')->unsigned();
            $table->timestamps();

            $table->softDeletes();

            $table->foreign("attribute_id")->references("id")->on("profile_attributes");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('attribute_values');
	}

}
