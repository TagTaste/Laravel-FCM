<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGenerationToQuizApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('quiz_applicants', function (Blueprint $table){
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
        Schema::table('quiz_applicants', function (Blueprint $table){
            $table->dropColumn('generation');
        });
    }
}
