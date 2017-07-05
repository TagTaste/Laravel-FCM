<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTvshowChannelsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_shows',function(Blueprint $table){
            $table->string('channel')->nullable()->change();
            $table->string('appeared_as')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_shows',function(Blueprint $table){
            $table->string('channel')->change();
            $table->string('appeared_as')->change();

        });
    }
}
