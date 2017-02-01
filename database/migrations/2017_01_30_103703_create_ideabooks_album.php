<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateIdeabooksAlbum extends Migration
{
    public function up()
    {
        Schema::create('ideabook_albums',function($table){
            $table->integer('ideabook_id')->unsigned();
            $table->integer('album_id')->unsigned();

            $table->foreign('ideabook_id')->references('id')->on('ideabooks')->onDelete('cascade');
            $table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ideabook_albums');
    }
}
