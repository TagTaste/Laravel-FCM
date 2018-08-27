<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProfileUnsubscribeReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        //
        Schema::create('profile_unsubscribe_reasons',function(Blueprint $table){
            $table->increments('id');
            $table->integer('profile_id');
            $table->integer('company_id')->nullable();
            $table->integer('reason_id');
            $table->string('action')->nullable();
            $table->string('model')->nullable();
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('reason_id')->references('id')->on('unsubscribe_reasons');
            $table->timestamps();
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
        Schema::drop('profile_unsubscribe_reasons');
    }
}
