<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_tracker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_name', 50)->nullable();
            $table->string('model_id');
            $table->integer('profile_id')->unsigned()->nullable();
            $table->string('interaction_type', 50)->nullable();
            $table->integer('interaction_type_id')->unsigned()->nullable();
            $table->string('device', 50)->nullable();
            $table->string('device_id', 255)->nullable();
            $table->timestamps();

            $table->foreign("profile_id")->references("id")->on("profiles");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_tracker');
    }
}
