<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuestionnaireQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('questionnaire_question_options', function(Blueprint $table){            
            $table->string('color')->nullable();
            $table->json('image')->nullable();            
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
        Schema::table('questionnaire_question_options', function(Blueprint $table){            
            $table->dropColumn('color');
            $table->dropColumn('image');
        });
    }
}
