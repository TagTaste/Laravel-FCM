<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SurveyCloseReason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys_close_reasons',function(Blueprint $table){
            $table->increments("id");
            $table->char('survey_id',36);
            $table->string("reason");
            $table->string("other_reason");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')  ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('survey_id')->references('id')->on('surveys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('surveys_close_reasons');
    }
}
