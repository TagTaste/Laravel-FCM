<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysAttemptMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('surveys_attempt_mapping', function(Blueprint $table){
            $table->increments('id');
            $table->uuid('survey_id');
            $table->unsignedInteger('profile_id');
            $table->integer('attempt')->default(1);
            $table->timestamp('completion_date')->nullable();            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->foreign('survey_id')->references("id")->on("surveys");
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
        //
        Schema::drop('surveys_attempt_mapping');
    }
}
