<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoShareLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_share_likes', function (Blueprint $table) {
             $table->unsignedInteger('photo_share_id');
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references('id')->on('profiles')->onDelete('cascade');
              $table->foreign("photo_share_id")->references('id')->on('photo_shares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photo_share_likes');
    }
}
