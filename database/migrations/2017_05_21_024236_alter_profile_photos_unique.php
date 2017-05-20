<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfilePhotosUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_photos',function(Blueprint $table){
            $table->integer("photo_id")->unsigned()->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_photos',function(Blueprint $table){
            $table->integer("photo_id")->unsigned()->change();
        });
    }
}
