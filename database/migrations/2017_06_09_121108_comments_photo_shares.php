<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentsPhotoShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('comments_photo_shares',function(Blueprint $table){
            $table->unsignedInteger('photo_share_id');
            $table->unsignedInteger('comment_id');
             $table->foreign("comment_id")->references('id')->on('comments');
              $table->foreign("photo_share_id")->references('id')->on('photo_shares');
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_photo_shares');

    }
}
