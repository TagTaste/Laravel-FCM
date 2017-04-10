<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdeabookProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ideabook_products',function(Blueprint $table){
            $table->integer('ideabook_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->text('note')->nullable();
            $table->foreign('ideabook_id')->references('id')->on('ideabooks');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ideabook_products');
    }
}
