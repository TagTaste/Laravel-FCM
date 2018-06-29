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
            $table->string("subtitle")->nullable();
            $table->integer("select_type")->default(0); // default 0 = multiselect
            $table->integer("intensity_type")->default(0); // default 0 = not intensity
            $table->integer("intensity_type_value")->default(0); //default 0 = integer
            $table->boolean("is_question_nested")->default(0); //default 0 = not nested question present
            $table->boolean("is_nested")->default(0);
            $table->unsignedInteger("parent_question_id")->nullable();
            $table->json("questions")->nullable();
            $table->unsignedInteger('collaborate_id');
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
            $table->unsignedInteger('tasting_type_id');
            $table->foreign("tasting_type_id")->references("id")->on("collaborate_tasting_header");
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
