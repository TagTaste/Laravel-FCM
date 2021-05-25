<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsSurveysShareLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys_share_likes', function (Blueprint $table) {
            $table->unsignedInteger('surveys_share_id');
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references('id')->on('profiles')->onDelete('cascade');
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
        Schema::dropIfExists('surveys_share_likes');
    }
}
