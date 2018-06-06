<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborate_addresses',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('collaborate_id');
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
            $table->string('city');
            $table->json('location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborate_addresses');
    }
}
