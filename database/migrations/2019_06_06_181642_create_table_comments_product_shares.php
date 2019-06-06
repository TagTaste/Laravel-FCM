<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCommentsProductShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_product_shares',function (Blueprint $table){
            $table->unsignedInteger('comment_id');
            $table->unsignedInteger('product_share_id');
            $table->foreign("comment_id")->references('id')->on('comments')->onDelete('cascade');
            $table->foreign("product_share_id")->references('id')->on('public_review_product_shares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_product_shares');
    }
}
