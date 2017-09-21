<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("profile_books",function(Blueprint $table){
            $table->integer("release_date")->nullable();
            $table->integer("release_month")->nullable();
            $table->integer("release_year")->nullable();
        });
        Schema::table("experiences",function(Blueprint $table){
            $table->integer("start_date")->nullable();
            $table->integer("start_month")->nullable();
            $table->integer("start_year")->nullable();
            $table->integer("end_date")->nullable();
            $table->integer("end_month")->nullable();
            $table->integer("end_year")->nullable();
        });
        Schema::table("education",function(Blueprint $table){
            $table->integer("start_date")->nullable();
            $table->integer("start_month")->nullable();
            $table->integer("start_year")->nullable();
            $table->integer("end_date")->nullable();
            $table->integer("end_month")->nullable();
            $table->integer("end_year")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("experiences",function(Blueprint $table){
            $table->text('designation')->change();
        });
    }
}
