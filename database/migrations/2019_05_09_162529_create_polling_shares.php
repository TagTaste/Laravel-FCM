<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollingShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polling_shares',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('poll_id');
            $table->unsignedInteger('payload_id')->nullable();
            $table->integer('privacy_id')->unsigned();
            $table->foreign('profile_id')->references("id")->on("profiles")->onDelete('cascade');
            $table->foreign('poll_id')->references("id")->on("poll_questions");
            $table->foreign('payload_id')->references("id")->on("channel_payloads")->onDelete('cascade');
            $table->foreign('privacy_id')->references('id')->on('privacies');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('polling_shares');
    }
}
