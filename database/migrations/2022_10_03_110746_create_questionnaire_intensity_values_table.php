<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireIntensityValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_intensity_values', function (Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->string('color')->nullable();
            $table->float('sort_order');
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('intensity_list_id');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('intensity_list_id')->references('id')->on('questionnaire_intensity_lists');
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
        Schema::drop('questionnaire_intensity_values');
    }
}
