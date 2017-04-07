<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdeabookProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ideabook_profiles',function(Blueprint $table){
            $table->integer('ideabook_id')->unsigned();
            $table->integer('profile_id')->unsigned();
            
            $table->foreign('ideabook_id')->references('id')->on('ideabooks');
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
        Schema::drop('ideabook_profiles');
    }
}
