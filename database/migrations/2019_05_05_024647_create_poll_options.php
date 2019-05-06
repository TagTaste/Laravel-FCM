<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("poll_options",function(Blueprint $table){
            $table->increments('id');
            $table->string('text');
            $table->unsignedInteger('poll_id')->nullable();
            $table->foreign('poll_id')->references("id")->on("poll_questions");
            $table->unsignedInteger('count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('poll_options');
    }
}
