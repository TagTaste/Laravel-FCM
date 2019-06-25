<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductShareLike extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('public_review_share_likes',function (Blueprint $table){
            $table->unsignedInteger('public_review_share_id');
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign("public_review_share_id")->references('id')->on('public_review_product_shares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('public_review_share_likes');
    }
}
