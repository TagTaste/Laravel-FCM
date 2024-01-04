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
            $table->tinyInteger('current_status')->after('completion_date')->unsigned()->nullable()->index(); 
            $table->timestamp('start_review')->after('current_status')->nullable()->index(); 
            $table->timestamp('end_review')->after('start_review')->nullable()->index(); 
            $table->bigInteger('duration')->after('end_review')->unsigned()->nullable()->index(); // in seconds (bigint)
            $table->boolean('is_flag')->after('duration')->default(false)->index(); // 0 or 1
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
            $table->dropColumn(['is_flag', 'duration', 'start_review', 'end_review', 'current_status']);
        });
    }
}
