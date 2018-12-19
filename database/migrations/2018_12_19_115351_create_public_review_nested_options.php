<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewNestedOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_review_nested_options',function(Blueprint $table){
            $table->increments('id');
            $table->integer("sequence_id");
            $table->integer("parent_id")->nullable();
            $table->string("value");
            $table->text('description')->nullable();
            $table->unsignedInteger("question_id");
            $table->boolean("is_active")->default(1);
            $table->boolean("is_nested_option")->default(0);
            $table->string("path")->nullable();
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
        Schema::drop('public_review_nested_options');
    }
}
