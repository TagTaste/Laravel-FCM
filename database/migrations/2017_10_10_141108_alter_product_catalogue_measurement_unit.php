<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductCatalogueMeasurementUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_catalogues',function(Blueprint $table){
            $table->string("measurement_unit")->nullable()->change();
            $table->string("certified")->nullable()->change();
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
            $table->float("measurement_unit")->nullable()->change();
            $table->string("certified")->nullable()->change();
        });
    }
}
