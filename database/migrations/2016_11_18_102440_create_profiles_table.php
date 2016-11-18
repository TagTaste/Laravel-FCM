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
            $table->integer('user_id')->unsigned();
            $table->integer('profile_attribute_id')->unsigned();
            $table->string('value');
            $table->integer('type_id')->unsigned();
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('user_id')->references("id")->on("users");
            $table->foreign('profile_attribute_id')->references("id")->on("profile_attributes");
            $table->foreign('type_id')->references("id")->on("profile_types");
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
