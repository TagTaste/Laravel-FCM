<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesEstablishmentTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("profile_establishment_types",function(Blueprint $table){
            $table->integer("profile_id")->unsigned();
            $table->integer("establishment_type_id")->unsigned();

            $table->foreign("profile_id")->references('id')->on('profiles');
            $table->foreign("establishment_type_id")->references('id')->on('establishment_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profile_establishment_types');
    }
}
