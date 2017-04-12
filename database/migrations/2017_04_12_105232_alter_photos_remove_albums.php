<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPhotosRemoveAlbums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos',function(Blueprint $table){
            $table->dropForeign(['album_id']);
            $table->dropColumn(['album_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photos',function(Blueprint $table){
            $table->integer('album_id')->unsigned();
            $table->foreign('album_id')->references("id")->on('albums');
        });
    }
}
