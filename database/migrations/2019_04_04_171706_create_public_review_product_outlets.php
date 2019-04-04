<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewProductOutlets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("public_review_product_outlets",function(Blueprint $table){
            $table->increments('id');
            $table->uuid("product_id")->nullable();
            $table->foreign('product_id')->references('id')->on('public_review_products')->onDelete('cascade');
            $table->string("vendor_name")->nullable();
            $table->string("vendor_code")->nullable();
            $table->string("mm_name")->nullable();
            $table->string("city")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('public_review_product_outlets');
    }
}
