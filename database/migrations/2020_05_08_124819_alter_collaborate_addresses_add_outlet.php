<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateAddressesAddOutlet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_addresses', function(Blueprint $table){
            $table->integer('outlet_id')->unsigned()->nullable();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->boolean('is_active')->default(1);
            $table->increments('address_id')->unsingned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_addresses', function(Blueprint $table){
            $table->dropForeign(['outlet_id']);
            $table->dropColumn(['outlet_id','is_active','address_id']);
        });
    }
}
