<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys_shares', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->char('surveys_id');
            $table->unsignedInteger('payload_id')->nullable();
            $table->integer('privacy_id')->unsigned();
            $table->text('content')->nullable();
            $table->foreign('profile_id')->references("id")->on("profiles")->onDelete('cascade');
            $table->foreign('surveys_id')->references("id")->on("surveys");
            $table->foreign('payload_id')->references("id")->on("channel_payloads")->onDelete('cascade');
            $table->foreign('privacy_id')->references('id')->on('privacies');
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
        Schema::table('surveys_shares', function (Blueprint $table) {
            //
            Schema::drop('surveys_shares');

        });
    }
}
