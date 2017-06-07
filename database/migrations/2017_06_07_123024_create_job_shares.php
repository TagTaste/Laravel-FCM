<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("job_shares",function(Blueprint $table){
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('payload_id');
            $table->unique(['job_id','profile_id']);
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('payload_id')->references("id")->on("channel_payloads");
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('job_shares');
    }
}
