<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewProductAllergens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_review_product_allergens', function (Blueprint $table) {
            $table->string('product_id');
            $table->integer('allergen_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('allergen_id')->references('id')->on('allergens');
            $table->foreign('product_id')->references('id')->on('public_review_products');

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('public_review_product_allergens');
    }
}
