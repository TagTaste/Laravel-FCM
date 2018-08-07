<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddressesAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("addresses",function(Blueprint $table){
            $table->text('house_no')->nullable();
            $table->text('landmark')->nullable();
            $table->renameColumn('address2','locality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table("addresses",function(Blueprint $table){
            $table->dropColumn('house_no');
            $table->dropColumn('landmark');
            $table->dropColumn('locality');    
        });
    }
}