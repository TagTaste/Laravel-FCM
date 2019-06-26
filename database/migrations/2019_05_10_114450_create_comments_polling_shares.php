<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsPollingShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_polling_shares',function(Blueprint $table){
            $table->unsignedInteger('polling_share_id');
            $table->unsignedInteger('comment_id');
            $table->foreign("comment_id")->references('id')->on('comments')->onDelete('cascade');
            $table->foreign("polling_share_id")->references('id')->on('polling_shares')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_polling_shares');

    }
}
