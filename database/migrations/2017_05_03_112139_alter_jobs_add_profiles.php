<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobsAddProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs',function(Blueprint $table){
            $table->integer('profile_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable()->change();
            
            $table->foreign('profile_id')->references('id')->on('profiles');
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
            $table->dropForeign('jobs_profile_id_foreign');
            $table->dropColumn("profile_id");
            
            $table->integer('company_id')->unsigned()->change();
        });
    }
}
