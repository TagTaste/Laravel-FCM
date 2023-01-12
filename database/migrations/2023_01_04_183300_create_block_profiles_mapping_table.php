<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockProfilesMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('block_profiles_mapping', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('blocked_profile_id')->nullable();
            $table->unsignedInteger('blocked_company_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('blocked_company_id')->references('id')->on('companies');
            $table->foreign('blocked_profile_id')->references('id')->on('profiles');
            
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
        Schema::drop('block_profiles_mapping');

    }
}
