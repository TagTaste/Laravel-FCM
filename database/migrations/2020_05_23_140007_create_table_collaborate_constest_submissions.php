<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCollaborateConstestSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_submissions', function(Blueprint $table){
            $table->integer('applicant_id')->unsigned();
            $table->integer('submission_id')->unsigned();
            $table->foreign('applicant_id')->references('id')->on('collaborate_applicants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contest_submissions');
    }
}
