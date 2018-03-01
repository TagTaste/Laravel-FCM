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
            $table->boolean('bell_notification');
            $table->boolean('push_notification');
            $table->boolean('email_notification');
            $table->boolean('bell_visibility');
            $table->boolean('push_visibility');
            $table->boolean('email_visibility');
            $table->string('action');
            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('settings');
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->index(['profile_id','action']);
            $table->index(['profile_id','setting_id']);
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
