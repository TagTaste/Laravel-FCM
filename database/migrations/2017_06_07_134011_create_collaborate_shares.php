<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id');
    
            $table->unsignedInteger('collaborate_id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('payload_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['collaborate_id','profile_id']);
            $table->foreign('collaborate_id')->references('id')->on('collaborates')->onDelete('cascade');
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
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
        Schema::drop("collaborate_shares");
    }
}
