<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireQuestionHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_question_helpers', function (Blueprint $table){
            $table->increments('id');
            $table->text('title');
            $table->text('video_link')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('question_id');
            
            $table->timestamps();
            $table->softDeletes();

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
        Schema::drop('questionnaire_question_helpers');
    }
}
