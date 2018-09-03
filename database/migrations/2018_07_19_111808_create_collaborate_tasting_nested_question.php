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
        Schema::create('collaborate_tasting_nested_options',function(Blueprint $table){
            $table->increments('id');
            $table->integer("sequence_id");
            $table->integer("parent_id")->nullable();
            $table->string("value");
            $table->unsignedInteger("question_id");
            $table->boolean("is_active")->default(1);
            $table->boolean("is_nested_option")->default(0);
            $table->string("path")->nullable();
            $table->unsignedInteger('header_type_id');
            $table->foreign("header_type_id")->references("id")->on("collaborate_tasting_header");
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
        Schema::drop('collaborate_tasting_nested_options');
    }
}
