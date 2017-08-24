<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicationSoftdelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("applications", function ($table) {
            $table->dropForeign('applications_job_id_foreign');
            $table->dropForeign('applications_profile_id_foreign');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("applications", function ($table) {
            $table->dropSoftDeletes();
            $table->dropForeign('applications_job_id_foreign');
            $table->dropForeign('applications_profile_id_foreign');
        });
    }
}
