<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductFilters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_filters', function(Blueprint $table){
            $table->string('key');
            $table->string("value");
            $table->integer("public_review_id")->unsigned();
            $table->increments('id');
            $table->foreign('public_review_id')->references('id')->on('public_review_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropTable("public_review_products");
    }
}
