<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicReviewProductsAddForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_products', function (Blueprint $table) {
            //
            $table->foreign('brand_id')->references('id')->on('public_review_product_brands');
            $table->foreign('company_id')->references('id')->on('public_review_product_companies');
            $table->foreign('city_id')->references('id')->on('public_review_cities');
            $table->foreign('type_id')->references('id')->on('public_review_product_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_review_products', function (Blueprint $table) {
            //
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['type_id']);
        });
    }
}
