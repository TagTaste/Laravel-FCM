<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicReviewProductsRemoveCityId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('public_review_products', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn(['city_id']);
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
        Schema::table('public_review_products', function (Blueprint $table){
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('public_review_cities');
        });
    }
}
