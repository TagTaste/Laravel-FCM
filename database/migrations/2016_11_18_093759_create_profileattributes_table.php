<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfileAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profile_attributes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('label');
            $table->string('description')->nullable();
            $table->boolean('multiline')->default(0);
            $table->boolean('requires_upload')->default(0);
            $table->string('allowed_mime_types')->nullable();
            $table->boolean('enabled')->default(0);
            $table->boolean('required')->default(0);

            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('template_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("parent_id")->references("id")->on("profile_attributes");
            $table->foreign("template_id")->references("id")->on("templates");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('profile_attributes');
	}

}
