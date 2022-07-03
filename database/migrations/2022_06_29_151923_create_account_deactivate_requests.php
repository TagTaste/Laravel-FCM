<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountDeactivateRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('account_deactivate_requests', function(Blueprint $table){
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->integer('reason_id')->unsigned();            
            $table->integer('account_management_id')->unsigned();
            
            $table->timestamp('deleted_on')->nullable();                
            $table->timestamp('reactived_on')->nullable();                
            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('account_deactivate_requests');
    }
}
