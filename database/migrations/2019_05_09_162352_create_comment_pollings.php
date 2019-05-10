<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentPollings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_pollings',function(Blueprint $table){
            $table->integer('comment_id')->unsigned();
            $table->integer('poll_id')->unsigned();

            $table->foreign("comment_id")->references('id')->on('comments');
            $table->foreign('poll_id')->references("id")->on("poll_questions");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comment_pollings');
    }
}
