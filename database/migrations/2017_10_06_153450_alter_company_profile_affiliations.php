<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyProfileAffiliations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies',function(Blueprint $table){
            $table->text("affiliations")->nullable();
        });
        Schema::table('profiles',function(Blueprint $table){
            $table->text("affiliations")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies',function(Blueprint $table){
            $table->dropColumn("affiliations");
        });
        Schema::table('profiles',function(Blueprint $table){
            $table->dropColumn("affiliations");
        });
    }
}
