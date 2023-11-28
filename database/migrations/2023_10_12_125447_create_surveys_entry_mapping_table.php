<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysEntryMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('surveys_entry_mapping', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('surveys_attempt_id')->unsigned();
            $table->foreign('surveys_attempt_id')->references("id")->on("surveys_attempt_mapping");

            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('surveys_entry_mapping');
    }
}
