<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireHeaderHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_header_helpers', function (Blueprint $table){
            $table->increments('id');
            $table->text('title')->nullable();
            $table->text('video_link')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('header_id');
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('header_id')->references('id')->on('questionnaire_headers');
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
        Schema::drop('questionnaire_header_helpers');

    }
}
