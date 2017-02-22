<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_addresses', function(Blueprint $table) {
            $table->increments('id');
            $table->text('address');
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->integer('company_id')->unsigned();
            $table->timestamps();

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
		Schema::drop('company_addresses');
	}

}
