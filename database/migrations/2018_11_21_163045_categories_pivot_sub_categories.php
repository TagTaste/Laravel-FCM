<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoriesPivotSubCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("categories_pivot_sub_categories",function(Blueprint $table){
            $table->integer("product_category_id")->unsigned();
            $table->integer("product_sub_category_id")->unsigned();

            $table->foreign("product_category_id")->references('id')->on('product_categories');
            $table->foreign("product_sub_category_id")->references('id')->on('product_sub_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories_pivot_sub_categories');
    }
}
