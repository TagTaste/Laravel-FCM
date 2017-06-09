<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id');
    
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('payload_id')->nullable();
//            $table->unique(['job_id','profile_id']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign('payload_id')->references("id")->on("channel_payloads")->onDelete('cascade');
    
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
