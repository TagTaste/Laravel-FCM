<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GlobalNestedOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('global_nested_option',function(Blueprint $table){
            $table->increments('id');
            $table->string('type')->nullable();
            $table->integer('s_no');
            $table->integer('parent_id')->nullable();
            $table->string('value')->nullable();
            $table->boolean('is_active');
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
        Schema::drop('global_nested_option');
    }
}
