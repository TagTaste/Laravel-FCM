<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesAllergens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("profiles_allergens",function(Blueprint $table){
            $table->integer("profile_id")->unsigned();
            $table->integer("allergens_id")->unsigned();

            $table->foreign("profile_id")->references('id')->on('profiles');
            $table->foreign("allergens_id")->references('id')->on('allergens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profiles_allergens');
    }
}
