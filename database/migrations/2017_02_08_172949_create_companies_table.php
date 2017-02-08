<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('companies', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('about');
            $table->text('logo')->nullable();
            $table->text('hero_image')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('registered_address')->nullable();
            $table->date('established_on')->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->foreign('status_id')->references('id')->on('company_statuses');
            $table->integer('type')->unsigned();
            $table->foreign('type')->references('id')->on('company_types');
            $table->bigInteger('employee_count')->default(0);
            $table->integer('client_count')->default(0);
            $table->bigInteger('annual_revenue_start')->default(0);
            $table->bigInteger('annual_revenue_end')->default(0);
            $table->text('milestones')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('pinterest_url')->nullable();
            $table->string('google_plus_url')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('companies');
	}

}
