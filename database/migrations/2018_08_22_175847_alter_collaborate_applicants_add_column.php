<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateApplicantsAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_applicants',function(Blueprint $table){
            $table->string("city")->nullable();
            $table->string("age_group")->nullable();
            $table->string("gender")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_applicants',function(Blueprint $table){
            $table->dropColumn(["city","age_group","gender"]);
        });
    }
}
