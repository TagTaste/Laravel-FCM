<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_info',function(Blueprint $table){
            $table->increments('id');
            $table->json('device_info')->nullable();
            $table->text('fcm_token')->nullable();
            $table->text('app_version')->nullable();
            $table->string('platform')->nullable();
            $table->unsignedInteger('profile_id');
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
        Schema::drop('app_info');
    }
}
