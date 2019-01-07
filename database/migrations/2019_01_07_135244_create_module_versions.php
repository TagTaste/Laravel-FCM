<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("interested_pivot_sub_category",function(Blueprint $table){
            $table->integer("interested_collection_id")->unsigned();
            $table->integer("product_sub_category_id")->unsigned();

            $table->foreign("interested_collection_id")->references('id')->on('interested_collections');
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
        Schema::drop('interested_pivot_sub_category');
    }
}
