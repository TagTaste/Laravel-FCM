<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGenerationToCollaborateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('collaborate_applicants', function (Blueprint $table){
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
        Schema::table('collaborate_applicants', function (Blueprint $table){
            $table->dropColumn('generation');
        });
    }
}
