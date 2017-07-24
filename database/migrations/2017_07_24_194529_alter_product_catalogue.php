<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductCatalogue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("product_catalogues",function(Blueprint $table){
            $table->float('price')->nullable();
            $table->float('moq')->nullable();
            $table->string('type')->nullable();
            $table->string('about')->nullable();
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
            $table->dropColumn('price');
            $table->dropColumn('moq');
            $table->dropColumn('type');
            $table->dropColumn('about');
        });
    }
}
