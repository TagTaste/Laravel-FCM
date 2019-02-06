<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSuggestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('product_suggestions', function(Blueprint $table){
            $table->increments('id');
            $table->string('product_name');
            $table->string('product_link')->nullable();
            $table->string('brand_name')->nullable();
            $table->boolean('is_live')->default(0);
            $table->string('image')->nullable();
            $table->unsignedInteger('profile_id');
            $table->foreign('profile_id')->references("id")->on("profiles");
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
        Schema::drop('product_suggestions');
    }
}
