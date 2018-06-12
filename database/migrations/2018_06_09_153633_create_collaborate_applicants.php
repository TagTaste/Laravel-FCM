<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateApplicants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborate_applicants',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('profile_id')->nullable();
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign("company_id")->references("id")->on("companies");
            $table->unsignedInteger('collaborate_id');
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
            $table->boolean('is_invited')->default(0);
            $table->dateTime('shortlisted_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->string('applier_address')->nullable();
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collaborate_applicants');
    }
}
