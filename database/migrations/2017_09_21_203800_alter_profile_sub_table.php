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
                $table->string("release_date")->change();
        });
        Schema::table("profile_shows",function(Blueprint $table){
            $table->string("date")->change();
        });
        Schema::table("projects",function(Blueprint $table){
            $table->string("completed_on")->change();
        });
        Schema::table("profile_patents",function(Blueprint $table){
            $table->string("publish_date")->change();
        });
        Schema::table("trainings",function(Blueprint $table){
            $table->string("completed_on")->change();
        });
        Schema::table("experiences",function(Blueprint $table){
            $table->string("start_date")->change();
            $table->string("end_date")->change();
        });
        Schema::table("education",function(Blueprint $table){
            $table->string("start_date")->change();
            $table->string("end_date")->change();
        });
        Schema::table("certifications",function(Blueprint $table){
            $table->string("date")->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("profile_books",function(Blueprint $table){
            $table->date("release_date")->change();
        });
        Schema::table("profile_shows",function(Blueprint $table){
            $table->date("date")->change();
        });
        Schema::table("projects",function(Blueprint $table){
            $table->date("completed_on")->change();
        });
        Schema::table("profile_patents",function(Blueprint $table){
            $table->date("publish_date")->change();
        });
        Schema::table("trainings",function(Blueprint $table){
            $table->date("completed_on")->change();
        });
        Schema::table("certifications",function(Blueprint $table){
            $table->date("date")->change();
        });
        Schema::table("education",function(Blueprint $table){
            $table->date("start_date")->change();
            $table->date("end_date")->change();
        });
        Schema::table("experiences",function(Blueprint $table){
            $table->date("start_date")->change();
            $table->date("end_date")->change();
        });
    }
}
