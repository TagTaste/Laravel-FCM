<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileSpecializations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("profile_specializations",function(Blueprint $table){
            $table->integer("profile_id")->unsigned();
            $table->integer("specialization_id")->unsigned();

            $table->foreign("profile_id")->references('id')->on('profiles');
            $table->foreign("specialization_id")->references('id')->on('specializations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profile_specializations');
    }
}
