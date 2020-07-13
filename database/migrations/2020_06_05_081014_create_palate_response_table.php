<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalateResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('palate_responses', function(Blueprint $table){
            $table->increments('id');
            $table->integer('iteration_id')->unsigned();
            $table->integer('profile_id')->unsigned();
            $table->integer('palate_option_id')->unsigned();
            $table->boolean('result')->nullable()->default(null);
            $table->integer('point_scale_result')->unsigned()->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('palate_option_id')->references('id')->on('palate_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('palate_responses');
    }
}
