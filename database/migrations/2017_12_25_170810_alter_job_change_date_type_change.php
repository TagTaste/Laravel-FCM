<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobChangeDateTypeChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs',function(Blueprint $table){
            $table->string("start_month")->nullable()->change();
            $table->string("start_year")->nullable()->change();
            $table->string("end_month")->nullable()->change();
            $table->string("end_year")->nullable()->change();

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
            $table->integer("start_month")->nullable()->change();
            $table->integer("start_year")->nullable()->change();
            $table->integer("end_month")->nullable()->change();
            $table->integer("end_year")->nullable()->change();

        });
    }
}
