<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateShortlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('collaborate_shortlist', function (Blueprint $table) {
            $table->integer('collaborate_id')->unsigned();
            $table->integer('profile_id')->unsigned();

            $table->foreign("collaborate_id")->references('id')->on('collaborates');
           $table->foreign("profile_id")->references('id')->on('profiles');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('collaborate_shortlist');
    }
}
