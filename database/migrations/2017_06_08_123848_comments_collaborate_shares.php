<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentsCollaborateShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_collaborate_shares',function(Blueprint $table){
            $table->unsignedInteger('collaborate_share_id');
            $table->unsignedInteger('comment_id');
            $table->foreign("comment_id")->references('id')->on('comments')->onDelete('cascade');
              $table->foreign("collaborate_share_id")->references('id')->on('collaborate_shares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_collaborate_shares');
    }
}
