<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SurveyApplicants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_applicants',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('profile_id')->nullable();
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign("company_id")->references("id")->on("companies");
            $table->char('survey_id',36);
            $table->foreign("survey_id")->references("id")->on("surveys");
            $table->boolean('is_invited')->default(0);
            $table->boolean('application_status')->default(1);
            $table->json('address')->nullable();            
            $table->text("message")->nullable();
            $table->string('hometown')->nullable();
            $table->string("city")->nullable();
            $table->string("current_city")->nullable();
            $table->string("age_group")->nullable();
            $table->string("gender")->nullable();
            $table->timestamp('completion_date')->nullable();            
            $table->softDeletes();
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
        Schema::drop('survey_applicants');
    }
}
