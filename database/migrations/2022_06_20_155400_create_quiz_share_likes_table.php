<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizShareLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('quiz_share_likes', function (Blueprint $table) {
            $table->unsignedInteger('quiz_share_id');
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign("quiz_share_id")->references('id')->on('quiz_shares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('quiz_share_likes');

    }
}
