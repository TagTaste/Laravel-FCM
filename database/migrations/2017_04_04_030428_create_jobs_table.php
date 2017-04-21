<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobs', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->string('type')->nullable();
            $table->string('location')->nullable();
            $table->string('annual_salary')->nullable();
            $table->string('functional_area')->nullable();
            $table->text('key_skills')->nullable();
            $table->string('xpected_role')->nullable(); //it is renamed later.
            $table->string('experience_required')->nullable();
            $table->integer('company_id')->unsigned();
            $table->dateTime("expires_on")->nullable();
            $table->timestamps();
            
            $table->softDeletes();
            
            $table->foreign("company_id")->references('id')->on('companies');
            
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jobs');
	}

}
