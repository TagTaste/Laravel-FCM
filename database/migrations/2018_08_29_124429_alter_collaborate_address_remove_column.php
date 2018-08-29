<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateAddressRemoveColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_addresses',function(Blueprint $table){
            Schema::drop('collaborate_addresses');
            Schema::create('collaborate_addresses',function(Blueprint $table){
                $table->unsignedInteger('collaborate_id');
                $table->foreign("collaborate_id")->references("id")->on("collaborates");
                $table->unsignedInteger('city_id');
                $table->foreign("city_id")->references("id")->on("cities");
                $table->integer("no_of_taster")->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_addresses',function(Blueprint $table){
            Schema::drop('collaborate_addresses');
            Schema::create('collaborate_addresses',function(Blueprint $table){
                $table->increments('id');
                $table->unsignedInteger('collaborate_id');
                $table->foreign("collaborate_id")->references("id")->on("collaborates");
                $table->string('city');
                $table->json('location')->nullable();
                $table->timestamp('deleted_at');
                $table->timestamps();
            });
        });
    }
}
