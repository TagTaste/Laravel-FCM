<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobsAddPayload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs',function (Blueprint $table){
           $table->integer('payload_id')->unsigned()->nullable();
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
        Schema::table("jobs",function(Blueprint $table){
            $table->dropColumn(['payload_id']);
        });
    }
}
