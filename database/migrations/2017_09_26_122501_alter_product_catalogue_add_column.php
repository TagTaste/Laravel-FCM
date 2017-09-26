<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductCatalogueAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_catalogues',function(Blueprint $table){
            $table->string('category')->nullable()->change();
            $table->string("brand")->nullable();
            $table->float("measurement_unit")->nullable();
            $table->text("barcode")->nullable();
            $table->text("size")->nullable();
            $table->boolean("certified")->nullable();
            $table->text("delivery_cities")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("product_catalogues",function(Blueprint $table){
            $table->string('category')->change();
            $table->dropColumn("brand");
            $table->dropColumn("measurement_unit");
            $table->dropColumn("barcode");
            $table->dropColumn("size");
            $table->dropColumn("certified");
            $table->dropColumn("delivery_cities");
        });

    }
}
