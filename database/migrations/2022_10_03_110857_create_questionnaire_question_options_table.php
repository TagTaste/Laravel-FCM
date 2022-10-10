<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_question_options', function (Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_intensity')->default(false);
            $table->integer('initial_intensity')->default(1);
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('option_type_id');
            $table->float('benchmark')->nullable();
            $table->json('intensity_value')->nullable();
            $table->unsignedInteger('question_id');
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('option_type_id')->references('id')->on('questionnaire_option_types');
            $table->foreign('question_id')->references('id')->on('questionnaire_questions');
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
        Schema::drop('questionnaire_question_options');

    }
}
