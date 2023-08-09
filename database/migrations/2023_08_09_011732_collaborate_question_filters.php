<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CollaborateQuestionFilters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborate_question_filters', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('profile_id')->unsigned();
            $table->integer('collaborate_id')->unsigned();
            $table->integer('batch_id')->unsigned();
            $table->json("value")->nullable();
            $table->foreign('profile_id')->references("id")->on("profiles");
            $table->foreign('collaborate_id')->references("id")->on("collaborates");
            $table->timestamps();
            $table->softDeletes();
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
    }
}
