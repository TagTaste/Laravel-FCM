<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireIntensityTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_intensity_templates', function (Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->integer('sort_order')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('scale')->nullable();

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
        Schema::drop('questionnaire_question_helpers');

    }
}
