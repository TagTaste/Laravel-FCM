<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewProductShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_review_product_shares',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('payload_id')->nullable();
            $table->integer('privacy_id')->unsigned();
            $table->foreign('profile_id')->references("id")->on("profiles")->onDelete('cascade');
            $table->foreign('product_id')->references("id")->on("public_review_products")->onDelete('cascade');
            $table->foreign('payload_id')->references("id")->on("channel_payloads")->onDelete('cascade');
            $table->foreign('privacy_id')->references('id')->on('privacies');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('public_review_product_shares');
    }
}
