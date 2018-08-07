<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddPath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('collaborate_tasting_nested_options',function(Blueprint $table){
            $table->string("path")->nullable();//initially every account is set to regular account
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('collaborate_tasting_nested_options',function(Blueprint $table){
//            $table->dropColumn("is_premium");//initially every account is set to regular account
//        });
    }
}
