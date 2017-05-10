<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsShoutouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('comments_shoutouts', function (Blueprint $table) {
            $table->integer('comment_id')->unsigned();
            $table->integer('shoutout_id')->unsigned();
            
            $table->foreign("comment_id")->references('id')->on('comments');
            $table->foreign("shoutout_id")->references('id')->on('shoutouts');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_recipes');
    }
}
