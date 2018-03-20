<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setting_id')->unsigned();
            $table->integer('profile_id')->unsigned();
            $table->integer('company_id')->unsigned()->nullable();
            $table->boolean('bell_visibility')->nullable();
            $table->boolean('email_visibility')->nullable();
            $table->boolean('push_visibility')->nullable();
            $table->boolean('bell_active')->nullable();
            $table->boolean('email_active')->nullable();
            $table->boolean('push_active')->nullable();
            $table->boolean('bell_value')->nullable();
            $table->boolean('email_value')->nullable();
            $table->boolean('push_value')->nullable();
            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('settings');
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->index(['profile_id','setting_id']);
            $table->index(['company_id','setting_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_settings');
    }
}
