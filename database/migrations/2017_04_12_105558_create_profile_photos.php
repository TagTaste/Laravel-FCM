<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilePhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_photos',function(Blueprint $table){
            $table->integer('photo_id')->unsigned();
            $table->integer("profile_id")->unsigned();
            
            $table->foreign("photo_id")->references('id')->on('photos');
            $table->foreign("profile_id")->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profile_photos');
    }
}
