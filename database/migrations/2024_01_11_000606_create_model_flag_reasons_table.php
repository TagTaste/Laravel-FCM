<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelFlagReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_flag_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('model_id');
            $table->unsignedInteger('flag_reason_id');
            $table->string('model');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('flag_reason_id')->references('id')->on('flag_reasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_flag_reasons');
    }
}
