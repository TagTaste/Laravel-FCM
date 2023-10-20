<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveBatchIdFromCollaborateQuestionFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_question_filters', function (Blueprint $table) {
            $table->dropColumn('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_question_filters', function (Blueprint $table) {
            $table->integer('batch_id')->unsigned();
        });
    }
}
