<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewQuestionHeaders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_review_question_headers',function(Blueprint $table){
            $table->increments('id');
            $table->string("header_type");
            $table->boolean("is_active")->default(1);
            $table->unsignedInteger('global_question_id');
            $table->foreign("global_question_id")->references("id")->on("public_review_global_questions");
            $table->json('header_info')->nullable();
            $table->integer('header_selection_type')->nullable();
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
        Schema::drop('public_review_question_headers');
    }
}
