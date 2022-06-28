<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('comment_quiz', function (Blueprint $table) {
            $table->integer('comment_id')->unsigned();
            $table->uuid('quiz_id');
            $table->foreign("comment_id")->references('id')->on('comments')->onDelete('cascade');;
            $table->foreign('quiz_id')->references("id")->on("quizes")->onDelete('cascade');;
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
        Schema::dropIfExists('comment_quiz');

    }
}
