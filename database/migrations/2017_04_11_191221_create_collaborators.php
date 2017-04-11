<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborators',function(Blueprint $table){
            $table->integer('collaborate_id')->unsigned();
            $table->integer('profile_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->dateTime('applied_on')->nullable();
            $table->dateTime('approved_on')->nullable();
            
            $table->foreign('collaborate_id')->references('id')->on('collaborates');
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborators');
    }
}
