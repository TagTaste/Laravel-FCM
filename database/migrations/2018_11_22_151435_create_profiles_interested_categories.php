<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesInterestedCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("profiles_interested_collections",function(Blueprint $table){
            $table->integer("profile_id")->unsigned();
            $table->integer("interested_collection_id")->unsigned();

            $table->foreign("profile_id")->references('id')->on('profiles');
            $table->foreign("interested_collection_id")->references('id')->on('interested_collections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profiles_interested_collections');
    }
}
