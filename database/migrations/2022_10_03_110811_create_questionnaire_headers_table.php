<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_headers', function (Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('header_type_id');
            $table->unsignedInteger('questionnaire_id');
            $table->string('question_order')->nullable();
            $table->boolean('is_active')->default(false);
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('header_type_id')->references('id')->on('questionnaire_header_types');

            $table->foreign('questionnaire_id')->references('id')->on('questionnaire_lists');
            
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
        Schema::drop('questionnaire_headers');

    }
}
