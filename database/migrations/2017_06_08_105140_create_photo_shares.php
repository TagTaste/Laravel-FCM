<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_shares',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('photo_id');
            $table->unsignedInteger('payload_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
//            $table->unique(['profile_id','photo_id']);
            $table->foreign('profile_id')->references("id")->on("profiles")->onDelete('cascade');
            $table->foreign('photo_id')->references("id")->on("photos")->onDelete('cascade');
            $table->foreign('payload_id')->references("id")->on("channel_payloads")->onDelete('cascade');
        
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
