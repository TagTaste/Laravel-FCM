<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobSalaryColumnTypeChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("jobs",function(Blueprint $table){
            $table->integer('salary_min')->unsigned()->nullable()->change();
            $table->integer('salary_max')->unsigned()->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs',function(Blueprint $table){
            $table->float('salary_min')->unsigned()->nullable();
            $table->float('salary_max')->unsigned()->nullable();
        });
    }
}
