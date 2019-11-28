<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_group_mapping', function(Blueprint $table){
            $table->integer('group_id')->unsigned();
            $table->integer('profile_id')->unsigned();

            $table->foreign('group_id')->references('id')->on('user_group');
            $table->foreign('profile_id')->references('id')->on('profiles');
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
        Schema::dropIfExists('user_group_mapping');
    }
}
