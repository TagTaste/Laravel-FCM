<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoutoutShareLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoutout_share_likes', function (Blueprint $table) {
             $table->unsignedInteger('shoutout_share_id');
            $table->unsignedInteger('profile_id');
             $table->foreign("profile_id")->references('id')->on('profiles');
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
        Schema::dropIfExists('shoutout_share_likes');
    }
}
