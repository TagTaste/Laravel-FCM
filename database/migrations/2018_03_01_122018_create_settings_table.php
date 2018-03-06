<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('bell_description')->nullable();
            $table->string('email_description')->nullable();
            $table->string('push_description')->nullable();
            $table->boolean('bell_visibility')->nullable();
            $table->boolean('email_visibility')->nullable();
            $table->boolean('push_visibility')->nullable();
            $table->boolean('bell_active')->nullable();
            $table->boolean('email_active')->nullable();
            $table->boolean('push_active')->nullable();
            $table->boolean('bell_value')->nullable();
            $table->boolean('email_value')->nullable();
            $table->boolean('push_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
