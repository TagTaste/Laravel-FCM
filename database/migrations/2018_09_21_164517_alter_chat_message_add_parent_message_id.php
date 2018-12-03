<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChatMessageAddParentMessageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('chat_messages',function(Blueprint $table){
            $table->unsignedInteger('parent_message_id')->nullable();
            $table->foreign("parent_message_id")->references("id")->on("chat_messages");
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
        Schema::table('chat_messages',function(Blueprint $table){
            $table->dropColumn('parent_message_id');
        });
    }
}
