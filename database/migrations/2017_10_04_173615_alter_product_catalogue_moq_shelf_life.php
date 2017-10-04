<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductCatalogueMoqShelfLife extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_catalogues',function(Blueprint $table){
            $table->string("price")->nullable()->change();
            $table->string("shelf_life")->nullable()->change();
            $table->string("moq")->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_catalogues',function(Blueprint $table){
            $table->double("price")->nullable()->change();
            $table->double("shelf_life")->nullable()->change();
            $table->double("moq")->nullable()->change();
        });
    }
}
