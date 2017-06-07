<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collaborate_shares",function(Blueprint $table){
            $table->unsignedInteger('collaborate_id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('payload_id');
            $table->unique(['collaborate_id','profile_id']);
            $table->foreign('collaborate_id')->references('id')->on('collaborates');
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('payload_id')->references("id")->on("channel_payloads");
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("collaborate_shares");
    }
}
