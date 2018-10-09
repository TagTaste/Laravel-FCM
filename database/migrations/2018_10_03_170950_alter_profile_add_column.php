<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles',function(Blueprint $table){
            $table->integer("foodie_type_id")->unsigned()->nullable();
            $table->foreign("foodie_type_id")->references('id')->on('foodie_type');
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
            $table->dropForeign("foodie_type_id");
            $table->dropColumn("foodie_type_id");

        });
    }
}
