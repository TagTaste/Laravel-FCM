<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profiles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('tagline');
            $table->text('about');
            $table->string('image');
            $table->string('hero_image')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('dob')->nullable();
            $table->text('interests')->nullable();
            $table->integer('marital_status',2)->nullable();
            $table->string('website_url')->nullable();
            $table->string('blog_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('youtube_channel')->nullable();
            $table->integer('followers')->unsigned()->default('0');
            $table->integer('following')->unsigned()->default('0');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("user_id")->references("id")->on("users");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('profiles');
	}

}
