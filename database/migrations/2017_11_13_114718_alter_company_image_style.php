<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyImageStyle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies',function(Blueprint $table){
            $table->text("style_logo")->nullable();
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
        Schema::table('companies',function(Blueprint $table){
            $table->dropColumn('style_logo');
            $table->dropColumn('style_hero_image');
        });
    }
}
