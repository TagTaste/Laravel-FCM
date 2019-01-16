<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicProductUserReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_product_user_review',function(Blueprint $table){
            $table->increments('id');
            $table->string("key")->nullable();
            $table->text("value");
            $table->unsignedInteger('value_id')->nullable();
            $table->unsignedInteger("leaf_id")->nullable();
            $table->integer('select_type')->nullable();
            $table->string("intensity")->nullable();
            $table->integer("current_status")->default(0);
            $table->json('meta')->nullable();
            $table->unsignedInteger('question_id');
            $table->foreign("question_id")->references("id")->on("public_review_questions");
            $table->unsignedInteger('header_id');
            $table->foreign("header_id")->references("id")->on("public_review_question_headers");
            $table->uuid("product_id")->nullable();
            $table->foreign('product_id')->references('id')->on('public_review_products')->onDelete('cascade');
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('public_product_user_review');
    }
}
