<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborationCloseReason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collaborate_close_reason",function(Blueprint $table){
            $table->increments('id');
            $table->text("reason")->nullable();
            $table->text("other_reason")->nullable();
            $table->unsignedInteger('collaborate_id');
            $table->foreign("collaborate_id")->references("id")->on("collaborates");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborate_close_reason');
    }
}
