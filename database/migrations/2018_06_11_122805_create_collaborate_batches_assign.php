<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateBatchesAssign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborate_batches_assign',function(Blueprint $table){
            $table->unsignedInteger('batch_id')->nullable();
            $table->foreign("batch_id")->references("id")->on("collaborate_batches");
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->boolean("begin_tasting")->defaul(0);
            $table->timestamp('last_seen')->nullable();
            $table->unsignedInteger('collaborate_id')->nullable();
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
            $table->timestamps();
            $table->timestamp('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborate_batches_assign');
    }
}
