<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobInternDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs',function(Blueprint $table){
            $table->integer("start_month")->nullable();
            $table->integer("start_year")->nullable();
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
        Schema::table('jobs',function(Blueprint $table){
            $table->dropColumn('start_month');
            $table->dropColumn('start_year');
            $table->dropColumn('end_month');
            $table->dropColumn('end_year');

        });
    }
}
