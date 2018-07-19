<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateTastingNestedQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborate_tasting_nested_question',function(Blueprint $table){
            $table->increments('id');
            $table->integer("sequence_id");
            $table->integer("parent_id")->nullable();
            $table->string("value");
            $table->unsignedInteger("question_id");
            $table->boolean("is_active")->default(1);
            $table->unsignedInteger('tasting_header_id');
            $table->foreign("tasting_header_id")->references("id")->on("collaborate_tasting_header");
            $table->unsignedInteger('collaborate_id');
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
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
        Schema::drop('collaborate_tasting_nested_question');
    }
}
