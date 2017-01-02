<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blog_articles', function(Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->string('image')->nullable();
            $table->integer('article_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

 			$table->foreign('article_id')->references('id')->on('articles');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('blog_articles');
	}

}
