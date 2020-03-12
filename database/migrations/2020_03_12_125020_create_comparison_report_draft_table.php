<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComparisonReportDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('comparison_report_draft', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->text('description')->nullable()->default(null);

            $table->json('products')->nullable()->default(null);
            $table->json('filters')->nullable()->default(null);
            $table->json('weights')->nullable()->default(null);
            $table->json('selected_questions')->nullable()->default(null);
            $table->json('final_report')->nullable()->default(null);

            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('comparison_report_draft');
    }
}
