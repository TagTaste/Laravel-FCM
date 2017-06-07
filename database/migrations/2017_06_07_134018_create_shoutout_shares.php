<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoutoutShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("shoutout_shares",function(Blueprint $table){
            $table->unsignedInteger('shoutout_id');
            $table->unsignedInteger('profile_id');
            $table->unique(['shoutout_id','profile_id']);
            $table->foreign('shoutout_id')->references('id')->on('shoutouts');
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shoutout_shares');
    }
}
