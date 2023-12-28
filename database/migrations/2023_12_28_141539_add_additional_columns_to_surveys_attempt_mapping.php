<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalColumnsToSurveysAttemptMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys_attempt_mapping', function (Blueprint $table) {
            $table->tinyInteger('is_flag')->after('completion_date')->unsigned()->default(0)->index(); // 0 or 1
            $table->bigInteger('duration')->after('is_flag')->unsigned()->nullable()->index(); // in seconds (bigint)
            $table->timestamp('start_review')->after('duration')->unsigned()->nullable()->index(); 
            $table->timestamp('end_review')->after('start_review')->unsigned()->nullable()->index(); 
            $table->tinyInteger('current_status')->after('end_review')->unsigned()->nullable()->index(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surveys_attempt_mapping', function (Blueprint $table) {
            $table->dropIndex(['is_flag', 'duration', 'start_review', 'end_review', 'current_status']);
        });
    }
}
