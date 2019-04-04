<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicReviewProductAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_products',function($table){
            $table->text("brand_description")->nullable();
            $table->text("company_description")->nullable();
            $table->text("paired_best_with")->nullable();
            $table->string("portion_size")->nullable();
            $table->int("serves_count")->nullable();
            $table->string("product_ingredients")->nullable();
            $table->json("nutritional_info")->nullable();
            $table->string("allergic_info_contains")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_review_products',function($table){
            $table->dropColumn(['brand_description','company_description','paired_best_with','portion_size',
                'product_ingredients','nutritional_info','allergic_info_contains']);
        });
    }
}
