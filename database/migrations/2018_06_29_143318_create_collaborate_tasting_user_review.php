<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateTastingUserReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborate_tasting_user_review',function(Blueprint $table){
            $table->increments('id');
            $table->string("key");
            $table->text("value");
            $table->unsignedInteger("aroma_id")->nullable();
            $table->unsignedInteger("aromatic_id")->nullable();
            $table->unsignedInteger('question_id');
            $table->foreign("question_id")->references("id")->on("collaborate_tasting_questions");
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
        Schema::drop('collaborate_tasting_user_review');
    }
}
