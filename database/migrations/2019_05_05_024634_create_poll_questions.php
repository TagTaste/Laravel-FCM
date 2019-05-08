<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("poll_questions",function(Blueprint $table){
            $table->increments('id');
            $table->text('title');
            $table->unsignedInteger('profile_id')->nullable();
            $table->foreign('profile_id')->references("id")->on("profiles");
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references("id")->on("companies");
            $table->integer("privacy_id")->unsigned()->default(1);
            $table->foreign("privacy_id")->references('id')->on('privacies');
            $table->integer('payload_id')->unsigned()->nullable();
            $table->foreign("payload_id")->references("id")->on('channel_payloads');
            $table->boolean('is_expired')->default(0);
            $table->dateTime('expired_time')->nullable();
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
        Schema::drop('poll_questions');
    }
}
