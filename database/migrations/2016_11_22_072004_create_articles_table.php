<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('author_id')->unsigned();
            $table->integer('privacy_id')->unsigned()->nullable();
            $table->boolean('comments_enabled')->default(1);
            $table->string('status');
            $table->integer('template_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("author_id")->references("id")->on("profiles");
            $table->foreign("privacy_id")->references("id")->on("privacies");
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
