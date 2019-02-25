<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqsQuestionAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs_question_answer', function(Blueprint $table){
            $table->increments('id');
            $table->text('question')->nullable();
            $table->text('answer')->nullable();
            $table->unsignedInteger('faq_category_id')->nullable();
            $table->foreign('faq_category_id')->references("id")->on("faq_categories");
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('faqs_question_answer');
    }
}
