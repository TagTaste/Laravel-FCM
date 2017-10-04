<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompaniesAllowDuplicateEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("companies",function(Blueprint $table){
            $table->dropUnique("companies_email_unique");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //This would fail although.
        Schema::table("companies",function(Blueprint $table){
            $table->unique('email');
        });
    }
}
