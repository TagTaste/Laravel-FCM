<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateAllergens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collaborate_allergens",function(Blueprint $table){
            $table->integer("collaborate_id")->unsigned();
            $table->integer("allergens_id")->unsigned();

            $table->foreign("collaborate_id")->references('id')->on('collaborates');
            $table->foreign("allergens_id")->references('id')->on('allergens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborate_allergens');
    }
}
