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
            $table->json('videos_meta')->after('image_meta')->nullable()->comment('JSON object for videos metadata'); 
            $table->json('assets_order')->default(json_encode(['videos', 'images']))->comment('Order of helper content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn(['videos_meta', 'assets_order']);
    }
}
