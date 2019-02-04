<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReviewTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('public_review_user_timings', function(Blueprint $table){
           $table->unsignedInteger('profile_id');
           $table->uuid('product_id');
            $table->foreign('profile_id')->references("id")->on("profiles")->onDelete('cascade');
            $table->foreign('product_id')->references("id")->on("public_review_products")->onDelete('cascade');
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
        //
        Schema::drop('public_review_user_timings');
    }
}
