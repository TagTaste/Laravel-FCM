<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValueIdProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles',function($table){
            $table->integer("value_id")->unsigned()->nullable();

            $table->foreign("value_id")->references('id')->on("attribute_values");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("profiles",function($table){
            $table->dropForeign(['value_id']);
            $table->dropColumn('value_id');
        });
    }
}
