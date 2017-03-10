<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfessionalDesignation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professionals',function(Blueprint $table){
            $table->dropColumn(['designation_id']);
            $table->string('designation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professionals',function(Blueprint $table){
            $table->dropColumn('designation');
            $table->integer('designation_id')->unsigned()->nullable();
            $table->foreign('designation_id')->references('id')->on('designations');
        });
    }
}
