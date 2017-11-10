<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileImageStyle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles',function(Blueprint $table){
            $table->text("style_image")->nullable();
            $table->text("style_hero_image")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles',function(Blueprint $table){
            $table->dropColumn('style_image');
            $table->dropColumn('style_hero_image');
        });
    }
}
