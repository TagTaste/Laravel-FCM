<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicReviewGlobalNestedOptionAddColumnAndForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('public_review_global_nested_option', function (Blueprint $table){
            $table->integer('aroma_list_id')->unsigned()->nullable();
            $table->foreign('aroma_list_id')->references('id')->on('public_review_aroma_list');
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
        Schema::table('public_review_global_nested_option', function (Blueprint $table) {
            $table->dropForeign(['aroma_list_id']);
            $table->dropColumn(['aroma_list_id']);
        });
    }
}
