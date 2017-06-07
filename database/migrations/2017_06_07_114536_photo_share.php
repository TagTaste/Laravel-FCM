<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PhotoShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_shares',function(Blueprint $table){
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('photo_id');
            
            $table->foreign('profile_id')->references("id")->on("profiles");
            $table->foreign('photo_id')->references("id")->on("photos");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('photo_shares');
    }
}
