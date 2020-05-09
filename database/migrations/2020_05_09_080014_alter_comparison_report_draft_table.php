<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterComparisonReportDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('comparison_report_draft', function (Blueprint $table) {
            $table->integer('questionnaire_id')->unsigned()->nullable();
            $table->boolean('is_private_review')->default(0);
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
        Schema::table('comparison_report_draft', function (Blueprint $table) {
            $table->dropColumn(['questionnaire_id']);
            $table->dropColumn(['is_private_review']);
        });

    }
}
