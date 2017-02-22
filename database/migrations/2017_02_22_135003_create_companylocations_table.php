<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_locations', function(Blueprint $table) {
            $table->increments('id');
            $table->text('address');
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->foreign('company_id');
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
		Schema::drop('company_locations');
	}

}
