<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('chats', function (Blueprint $table){
            $table->string('model_name')->nullable();
            $table->string('model_id')->nullable();
            $table->integer('batch_id')->nullable();
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
        Schema::table('chats', function(Blueprint $table){
            $table->dropColumn(['model_name','model_id','batch_id']);
        });
    }
}
