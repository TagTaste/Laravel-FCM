<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentsCollaborates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_collaborates',function($table){
            $table->integer('comment_id')->unsigned();
            $table->integer('collaborate_id')->unsigned();
        
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('collaborate_id')->references('id')->on('collaborates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_collaborates');
    }
}
