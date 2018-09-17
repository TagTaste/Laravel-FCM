<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateSpecializations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collaborate_specializations",function(Blueprint $table){
            $table->integer("collaborate_id")->unsigned();
            $table->integer("specialization_id")->unsigned();

            $table->foreign("collaborate_id")->references('id')->on('collaborates');
            $table->foreign("specialization_id")->references('id')->on('specializations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborate_specializations');
    }
}
