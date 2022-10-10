<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_questions', function (Blueprint $table){
            $table->increments('id');
            $table->text('title');
            $table->text('sub_title')->nullable();
            $table->integer('select_type');
            $table->boolean('is_mandatory')->default(false);
            $table->unsignedInteger('header_id');
            $table->string('option_order')->nullable();
            $table->boolean('can_select_parent')->default(false);
            $table->string('nested_option_list')->nullable();
            $table->string('nested_option_title')->nullable();
            $table->boolean('is_nested_option')->default(false);
            $table->json('intensity_value')->nullable();

            $table->boolean('is_active')->default(false);
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('header_id')->references('id')->on('questionnaire_headers');
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
        Schema::drop('questionnaire_questions');

    }
}
