<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GlobalQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('global_questions',function(Blueprint $table){
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
        Schema::drop('global_questions');
    }
}
