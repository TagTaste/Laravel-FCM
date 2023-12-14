<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntensityListToQuestionnaireOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('questionnaire_question_options', function (Blueprint $table) {
            $table->integer('intensity_list_id')->nullable();
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
            $table->dropColumn('intensity_list_id');
        });
    }
}
