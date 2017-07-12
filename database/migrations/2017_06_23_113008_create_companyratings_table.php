<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyRatingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_ratings', function(Blueprint $table) {
            $table->integer('company_id')->unsigned();
            $table->integer('profile_id')->unsigned();
            $table->unique(array('company_id', 'profile_id'));
            $table->float('rating');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_ratings');
	}

}
