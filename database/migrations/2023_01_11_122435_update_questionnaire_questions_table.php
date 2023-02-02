<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuestionnaireQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('questionnaire_questions', function(Blueprint $table){
            $table->text('placeholder')->nullable();            
            $table->boolean('is_intensity')->default(false);
            $table->integer('initial_intensity')->default(1);
            $table->text('title')->nullable()->change();
            $table->json('min_selection')->nullable();
            $table->json('max_selection')->nullable();
            $table->unsignedInteger('food_shot_id')->nullable();


            $table->foreign('food_shot_id')->references('id')->on('questionnaire_food_shot_placeholders');
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
        Schema::table('questionnaire_questions', function(Blueprint $table){
            $table->dropColumn('placeholder');
            $table->dropColumn('is_intensity');
            $table->dropColumn('initial_intensity');
            $table->dropColumn('min_selection');
            $table->dropColumn('max_selection');
            $table->dropForeign(['food_shot_id']);
            $table->dropColumn('food_shot_id');

        });
    }
}
