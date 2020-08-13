<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePublicProductUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_product_user_info', function(Blueprint $table){
            $table->unsignedInteger('profile_id');
            $table->string('product_id');
            $table->string('hometown');
            $table->string('city');
            $table->string('ageGroup');
            $table->string('gender');
            $table->string('designation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('public_product_user_info');
    }
}
