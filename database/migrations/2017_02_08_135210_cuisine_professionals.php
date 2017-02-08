<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CuisineProfessionals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuisine_professionals',function($table){
            $table->integer('professional_id')->unsigned();
            $table->integer('cuisine_id')->unsigned();

            $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('cascade');
            $table->foreign('cuisine_id')->references('id')->on('cuisines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cuisine_professionals');
    }
}
