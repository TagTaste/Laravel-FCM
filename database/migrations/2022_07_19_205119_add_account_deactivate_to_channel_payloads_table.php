<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountDeactivateToChannelPayloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::table('channel_payloads', function(Blueprint $table){
            $table->boolean('account_deactivated')->default(false);            
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
        Schema::table('channel_payloads', function(Blueprint $table){
            $table->dropColumn('account_deactivated');
        });
    }
}
