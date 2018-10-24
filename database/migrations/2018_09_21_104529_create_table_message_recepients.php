<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMessageRecepients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('message_recepients',function(Blueprint $table){
            $table->unsignedInteger('recepient_id');
            $table->foreign("recepient_id")->references("id")->on("profiles");
            $table->unsignedInteger('sender_id');
            $table->foreign("sender_id")->references("id")->on("profiles");
            $table->unsignedInteger('message_id');
            $table->foreign("message_id")->references("id")->on("chat_messages");
            $table->unsignedInteger('chat_id');
            $table->foreign("chat_id")->references("id")->on("chats");
            $table->timestamp('read_on')->nullable();
            $table->timestamp('sent_on')->nullable();
            $table->timestamp('deleted_on')->nullable();
            $table->boolean("is_clear")->default(0);
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
        Schema::drop('message_recepients');
    }
}
