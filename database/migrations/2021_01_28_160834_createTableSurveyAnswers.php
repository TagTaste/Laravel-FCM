<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTableSurveyAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger('profile_id');
            $table->char('survey_id', 36);
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('question_type');
            $table->unsignedInteger('option_type');
            $table->unsignedInteger('option_id');
            $table->json('image_meta');
            $table->json('video_meta');
            $table->json('document_meta');
            $table->json('media_url');            
            $table->text('answer_value');
            $table->boolean('current_status');
            $table->boolean('is_active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->foreign('profile_id')->references('id')->on('profiles');
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
        Schema::drop('survey_answers');
    }
}
