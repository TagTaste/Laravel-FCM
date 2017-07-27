<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicationShortlistedChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications',function(Blueprint $table){
            $table->smallInteger('shortlisted')->tinyInteger('shortlisted')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications',function(Blueprint $table){
            $table->boolean('shortlisted')->default(0)->changes();
        });
    }
}
