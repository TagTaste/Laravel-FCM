<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompaniesSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("awards",function(Blueprint $table){
            $table->string("date")->change();
        });
        Schema::table("company_books",function(Blueprint $table){
            $table->string("release_date")->change();
        });
        Schema::table("company_patents",function(Blueprint $table){
            $table->string("awarded_on")->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("awards",function(Blueprint $table){
            $table->date("date")->change();
        });
        Schema::table("company_books",function(Blueprint $table){
            $table->date("release_date")->change();
        });
        Schema::table("company_patents",function(Blueprint $table){
            $table->date("awarded_on")->change();
        });

    }
}
