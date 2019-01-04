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
            $table->increments('id');
            $table->string('key');
            $table->string("value");
            $table->uuid("product_id")->nullable();
            $table->foreign('product_id')->references('id')->on('public_review_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("product_filters");
    }
}
