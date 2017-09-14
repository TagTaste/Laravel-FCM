<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobApplicationUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("applications",function(Blueprint $table){
            $table->unique(array('job_id', 'profile_id'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("applications",function(Blueprint $table){
            $table->dropUnique('applications_job_id_profile_id_unique	');
        });
    }
}
