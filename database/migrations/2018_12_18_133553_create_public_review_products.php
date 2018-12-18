<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("public_review_products",function(Blueprint $table){
            $table->uuid('id')->primary();
            $table->string("name")->nullable();
            $table->boolean('is_vegetarian')->default(0);
            $table->integer("product_category_id")->unsigned();
            $table->foreign("product_category_id")->references('id')->on('product_categories');
            $table->integer("product_sub_category_id")->unsigned();
            $table->foreign("product_sub_category_id")->references('id')->on('product_sub_categories');
            $table->string('brand_name');
            $table->string('brand_logo');
            $table->string('company_name');
            $table->string('company_logo');
            $table->integer("company_id")->unsigned()->nullable();
            $table->foreign("company_id")->references('id')->on('companies');
            $table->string("description")->nullable();
            $table->boolean('mark_featured')->default();
            $table->json('images')->nullable();
            $table->string('video_link')->nullable();
            $table->unsignedInteger('global_question_id');
            $table->foreign("global_question_id")->references("id")->on("public_review_global_questions");
            $table->boolean("is_active")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('public_review_products');
    }
}
