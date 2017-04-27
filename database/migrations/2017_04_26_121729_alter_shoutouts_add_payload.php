<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShoutoutsAddPayload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shoutouts',function(Blueprint $table){
            $table->integer('payload_id')->unsigned()
                ->nullable(); //since there is historic data
           $table->foreign('payload_id')->references('id')->on('channel_payloads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shoutouts',function(Blueprint $table){
            $table->dropForeign("shoutouts_payload_id_foreign");
            $table->dropColumn("payload_id");
        });
    }
}
