<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProjectAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("projects",function(Blueprint $table){
            $table->dropColumn("ongoing");
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->date("completed_on")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("projects",function(Blueprint $table){
            $table->boolean('ongoing')->default('0');
            $table->date('start_date');
            $table->date('end_date');
            $table->dropColumn('completed_on');
        });
    }
}
