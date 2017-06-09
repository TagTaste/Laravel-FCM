<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentsShoutoutShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('comments_shoutout_shares',function(Blueprint $table){
            $table->unsignedInteger('shoutout_share_id');
            $table->unsignedInteger('comment_id');
            $table->foreign("comment_id")->references('id')->on('comments');
              $table->foreign("shoutout_share_id")->references('id')->on('shoutout_shares');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_shoutout_shares');
    }
}
