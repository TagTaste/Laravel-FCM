<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewGlobalQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('public_review_global_questions',function(Blueprint $table){
            $table->increments('id');
            $table->text('name')->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->json('question_json')->nullable();
            $table->json("header_info")->nullable();
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
        //
        Schema::drop('public_review_global_questions');
    }
}
