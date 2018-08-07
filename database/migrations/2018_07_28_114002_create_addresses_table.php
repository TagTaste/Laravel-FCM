<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('profile_addresses',function(Blueprint $table){
            $table->increments('id');
            $table->text('label')->nullable();
            $table->text('house_no')->nullable();
            $table->text('landmark')->nullable();
            $table->longtext('locality')->nullable();
            $table->longtext('address1')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('country')->nullable();
            $table->integer('pincode')->nullable();
            $table->unsignedInteger('profile_id');
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->timestamps();
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
        Schema::drop('addresses');
    }
}
