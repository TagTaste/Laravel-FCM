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
            $table->unsignedInteger('user_id');
            $table->foreign("user_id")->references("id")->on("users");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('app');
    }
}
