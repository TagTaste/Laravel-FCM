<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborationLike extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collaboration_likes",function(Blueprint $table){
            $table->integer("collaboration_id")->unsigned();
            $table->integer("profile_id")->unsigned();
            
            $table->foreign("collaboration_id")->references('id')->on('collaborates');
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
        Schema::drop('collaboration_likes');
    }
}
