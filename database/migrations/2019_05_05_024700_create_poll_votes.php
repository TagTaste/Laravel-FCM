<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollVotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("poll_votes",function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('profile_id')->nullable();
            $table->foreign('profile_id')->references("id")->on("profiles");
            $table->unsignedInteger('poll_id')->nullable();
            $table->foreign('poll_id')->references("id")->on("poll_questions");
            $table->unsignedInteger('poll_option_id')->nullable();
            $table->foreign('poll_option_id')->references("id")->on("poll_options");
            $table->string('ip_address')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('poll_votes');
    }
}
