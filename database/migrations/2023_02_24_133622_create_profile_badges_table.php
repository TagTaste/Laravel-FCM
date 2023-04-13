<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_badges', function(Blueprint $table) {
            $table->increments('id');
            
            $table->integer('profile_id')->unsigned()->nullable();
            $table->integer('badge_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->foreign("badge_id")->references("id")->on("badges");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profile_badges');
    }
}
