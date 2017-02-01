<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIdeabookArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ideabook_articles', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ideabook_id')->unsigned();
            $table->integer('article_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("ideabook_id")->references("id")->on("ideabooks")->onDelete('cascade');
            $table->foreign("article_id")->references("id")->on("articles")->onDelete('cascade');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ideabook_articles');
	}

}
