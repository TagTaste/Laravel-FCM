<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlagReasonConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flag_reason_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('flag_reason_id');
            $table->string('condition_value');
            $table->string('condition_slug');
            $table->string('condition_description');
            $table->timestamps();

            // Define foreign key constraint
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
        Schema::dropIfExists('flag_reason_conditions');
    }
}
