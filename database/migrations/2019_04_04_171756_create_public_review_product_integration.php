<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewProductIntegration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("public_review_product_integration",function(Blueprint $table){
            $table->increments('id');
            $table->uuid("product_id")->nullable();
            $table->foreign('product_id')->references('id')->on('public_review_products')->onDelete('cascade');
            $table->unsignedInteger("fp_product_id");
            $table->unsignedInteger("fp_type_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('public_review_product_integration');
    }
}
