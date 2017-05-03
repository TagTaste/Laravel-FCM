<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayloadsAddClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channel_payloads',function(Blueprint $table){
            $table->string('model')->nullable();
            $table->integer('model_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channel_payloads',function(Blueprint $table){
            $table->string('model')->nullable();
            $table->integer('model_id')->unsigned();
        });
    }
}
