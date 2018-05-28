<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileFiltersIndexAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("profile_filters",function(Blueprint $table){
            $table->increments('id');
        });
        Schema::table("company_filters",function(Blueprint $table){
            $table->increments('id');
        });
        Schema::table("collaborate_filters",function(Blueprint $table){
            $table->increments('id');
        });
        Schema::table("job_filters",function(Blueprint $table){
            $table->increments('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("profile_filters",function(Blueprint $table){
            $table->dropColumn('id');
        });
        Schema::table("company_filters",function(Blueprint $table){
            $table->dropColumn('id');
        });
        Schema::table("collaborate_filters",function(Blueprint $table){
            $table->dropColumn('id');
        });
        Schema::table("job_filters",function(Blueprint $table){
            $table->dropColumn('id');
        });
    }
}
