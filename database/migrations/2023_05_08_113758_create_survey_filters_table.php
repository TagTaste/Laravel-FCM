<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('survey_filters', function (Blueprint $table) {
            //
            $table->increments('id');

            $table->integer('profile_id')->unsigned();
            $table->char('surveys_id',36);
            $table->json("value")->nullable();
            $table->foreign('profile_id')->references("id")->on("profiles");
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
        Schema::dropIfExists('survey_filters');
    }
}
