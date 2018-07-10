<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateTastingQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborate_tasting_questions',function(Blueprint $table){
            $table->increments('id');
            $table->string("title");
            $table->text("subtitle")->nullable();
            $table->boolean("is_nested")->default(0);
            $table->boolean("is_mandatory")->default(0);
            $table->unsignedInteger("parent_question_id")->nullable();
            $table->json("questions")->nullable();
            $table->boolean("is_active")->default(1);
            $table->unsignedInteger('collaborate_id');
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
            $table->unsignedInteger('header_type_id');
            $table->foreign("header_type_id")->references("id")->on("collaborate_tasting_header");
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
        Schema::drop('collaborate_tasting_questions');
    }
}
