<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReviewBatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_review_batches_color',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('product_review_batches',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->text('notes')->nullable();
            $table->json('allergens')->nullable();
            $table->text('instruction')->nullable();
            $table->unsignedInteger('color_id');
            $table->foreign("color_id")->references("id")->on("product_review_batches_color");
            $table->unsignedInteger('collaborate_id');
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
            $table->unique(['name', 'collaborate_id']);
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
        Schema::drop('product_review_batches');
        Schema::drop('product_review_batches_color');

    }
}
