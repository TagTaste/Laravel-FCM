<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('articles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('privacy_id')->unsigned()->nullable();
            $table->boolean('comments_enabled')->default(1);
            $table->string('status');
            $table->integer('template_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("privacy_id")->references("id")->on("privacies");
            $table->foreign("user_id")->references("id")->on("users");
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
		Schema::drop('articles');
	}

}
