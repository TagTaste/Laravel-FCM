<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsSurveysShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_surveys_shares', function (Blueprint $table) {
            $table->unsignedInteger('surveys_share_id');
            $table->unsignedInteger('comment_id');
            $table->foreign("comment_id")->references('id')->on('comments')->onDelete('cascade');
            $table->foreign("surveys_share_id")->references('id')->on('surveys_shares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments_surveys_shares');
    }
}
