<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizesShareLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('quizes_share_likes', function (Blueprint $table) {
            $table->unsignedInteger('quizes_share_id');
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign("quizes_share_id")->references('id')->on('quizes_shares')->onDelete('cascade');
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
        Schema::dropIfExists('quizes_share_likes');

    }
}
