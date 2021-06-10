<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activity_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('profile_id')->nullable();
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->string('method')->nullable();
            $table->text("url")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_activity_tracking');
    }
}
