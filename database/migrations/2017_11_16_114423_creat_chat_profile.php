<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatChatProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_profiles',function(Blueprint $table){
            $table->unsignedInteger('chat_id');
            $table->foreign("chat_id")->references("id")->on("chats");
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references("id")->on("profiles");
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
        Schema::drop('chat_profiles');
    }
}
