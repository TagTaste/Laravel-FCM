<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_users',function(Blueprint $table){
		 
			$table->increments('id');
			$table->integer('company_id')->unsigned();
            $table->integer('user_id')->unsigned();
			$table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies');
			$table->foreign('user_id')->references('id')->on('users');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_users');
	}

}
