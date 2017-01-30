<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateIdeabooksPhotos extends Migration
{
    public function up()
    {
        Schema::create('ideabook_photos',function($table){
            $table->integer('ideabook_id')->unsigned();
            $table->integer('photo_id')->unsigned();

            $table->foreign('ideabook_id')->references('id')->on('ideabooks');
            $table->foreign('photo_id')->references('id')->on('photos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ideabook_photos');
    }
}
