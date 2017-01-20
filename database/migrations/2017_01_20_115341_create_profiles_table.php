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
            $table->text('tagline')->nullable();
            $table->text('about')->nullable();
            $table->string('image')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('dob')->nullable();
            $table->text('interests')->nullable();
            $table->string('website_url')->nullable();
            $table->string('blog_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('youtube_channel')->nullable();
            $table->unsignedInteger('followers')->default('0');
            $table->unsignedInteger('following')->default('0');
            $table->unsignedInteger('user_id');
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
