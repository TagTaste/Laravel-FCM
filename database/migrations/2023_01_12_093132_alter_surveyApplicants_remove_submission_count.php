<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSurveyApplicantsRemoveSubmissionCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_applicants', function (Blueprint $table) {
            $table->dropColumn('submission_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_applicants', function(Blueprint $table){
            $table->integer('submission_count')->default(0);
        });
        
    }
}
