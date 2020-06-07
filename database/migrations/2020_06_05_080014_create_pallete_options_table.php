<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalleteOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallete_options', function(Blueprint $table){
            $table->increments('id');
            $table->string('type');
            $table->boolean('has_concentration')->default(0);
            $table->string('concentration')->nullable();
            $table->integer('concentration_level')->unsigned()->nullable();
            $table->boolean('has_point_scale')->default(0);
            $table->integer('lower_point_scale')->unsigned()->nullable();
            $table->integer('upper_point_scale')->unsigned()->nullable();
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
        Schema::drop('pallete_options');
    }
}
