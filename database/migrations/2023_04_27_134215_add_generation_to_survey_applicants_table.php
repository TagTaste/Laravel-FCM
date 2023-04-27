<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGenerationToSurveyApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('survey_applicants', function (Blueprint $table){
            $table->string('generation')->nullable()->after('age_group');
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
        Schema::table('survey_applicants', function (Blueprint $table){
            $table->dropColumn('generation');
        });
    }
}
