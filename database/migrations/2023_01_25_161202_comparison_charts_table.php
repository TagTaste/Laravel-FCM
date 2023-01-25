<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ComparisonChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('comparison_charts', function (Blueprint $table){
            $table->increments('id');
            $table->string('collaborate_id')->nullable();
            $table->string('product_id')->nullable();
            $table->text('color_json')->nullable();

            $table->timestamps();
            $table->softDeletes();            
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
        Schema::drop('comparison_charts');
    }
}
