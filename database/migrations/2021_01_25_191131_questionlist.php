<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Questionlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_question_type', function(Blueprint $table){
            $table->unsignedInteger("question_type_id")->nullable();
            $table->unsignedInteger("sort_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_question_type',function(Blueprint $table){
            $table->dropColumn(['question_type_id','sort_id']);
        });
    }
}
