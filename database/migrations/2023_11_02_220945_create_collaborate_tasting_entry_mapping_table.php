<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateTastingEntryMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('collaborate_tasting_entry_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->integer('collaborate_id')->unsigned();
            $table->integer('batch_id')->unsigned();
            $table->integer('header_id')->unsigned()->nullable();
            $table->string('activity')->nullable();            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
            $table->foreign("batch_id")->references("id")->on("collaborate_batches");
            $table->foreign("header_id")->references("id")->on("collaborate_tasting_header");


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
        Schema::dropIfExists('collaborate_tasting_entry_mapping');
    }
}
