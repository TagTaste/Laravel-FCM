<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobsNullableExperience extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("jobs",function(Blueprint $table){
            $table->float('experience_min')->unsigned()->nullable()->change();
            $table->float('experience_max')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("jobs",function(Blueprint $table){
            $table->float('experience_min')->unsigned()->change();
            $table->float('experience_max')->unsigned()->change();
        });
    }
}
