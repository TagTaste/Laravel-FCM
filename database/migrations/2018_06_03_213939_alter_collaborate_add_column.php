<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('collaborate_categories');
            $table->integer("step")->nullable();
            $table->integer('financial_min')->nullable();
            $table->integer('financial_max')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id','step','financial_min','financial_max']);
        });
    }
}
