<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRecipesAddPayload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipes',function(Blueprint $table){
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
        Schema::table('recipes',function(Blueprint $table){
            $table->dropForeign("recipes_payload_id_foreign");
            $table->dropColumn("payload_id");
        });
    }
}
