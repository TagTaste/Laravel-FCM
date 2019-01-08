<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_review_questions',function(Blueprint $table){
            $table->increments('id');
            $table->string("title");
            $table->text("subtitle")->nullable();
            $table->boolean("is_nested_question")->default(0);
            $table->boolean("is_mandatory")->default(0);
            $table->unsignedInteger("parent_question_id")->nullable();
            $table->json("questions")->nullable();
            $table->boolean("is_active")->default(1);
            $table->unsignedInteger('global_question_id');
            $table->foreign("global_question_id")->references("id")->on("public_review_global_questions");
            $table->unsignedInteger('header_id');
            $table->foreign("header_id")->references("id")->on("public_review_question_headers");
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
        Schema::drop('public_review_questions');
    }
}
