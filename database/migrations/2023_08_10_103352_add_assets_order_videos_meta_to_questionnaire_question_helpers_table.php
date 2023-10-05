<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssetsOrderVideosMetaToQuestionnaireQuestionHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_question_helpers', function (Blueprint $table) {
            // $table->json('videos_meta')->after('images')->nullable(); 
            // $table->string('assets_order')->default(json_encode(['videos', 'images']))->after('videos_meta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_question_helpers', function(Blueprint $table){
            // $table->dropColumn(['videos_meta', 'assets_order']);
        });
    }
}
