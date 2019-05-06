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
