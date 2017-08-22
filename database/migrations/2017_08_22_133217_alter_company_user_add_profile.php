<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyUserAddProfile extends Migration
{
    private $table = 'company_users';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->table,function(Blueprint $table){
            
            //make user_id nullable
            $table->integer('user_id')->unsigned()->nullable()->change();
            
            //add profile id
            $table->integer('profile_id')->unsigned()->nullable();
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
        Schema::table($this->table,function(Blueprint $table){
            $table->integer('user_id')->unsigned()->change();
            
            $table->dropColumn(['profile_id']);
        });
    }
}
