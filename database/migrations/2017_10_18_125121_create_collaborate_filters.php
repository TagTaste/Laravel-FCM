<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateFilters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collaborate_filters",function(Blueprint $table){
            $table->string('key');
            $table->string("value");
            $table->integer("collaborate_id")->unsigned();
        
            $table->foreign('collaborate_id')->references('id')->on('collaborates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropTable('collaborate_filters');
    }
}
