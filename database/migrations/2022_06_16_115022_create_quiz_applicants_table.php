<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('quiz_applicants',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('profile_id')->nullable();
            $table->uuid('quiz_id');
            $table->integer('application_status')->default(1);
            $table->double('score');
            $table->string("age_group")->nullable();
            $table->timestamp('completion_date')->nullable();            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->foreign("profile_id")->references("id")->on("profiles");




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
        Schema::drop('quiz_applicants');
    }
}
