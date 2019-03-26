<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstantVariableModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('constant_variable_model', function(Blueprint $table){
            $table->increments('id');
            $table->string("model_name");
            $table->string("model_id");
            $table->string("ui_type")->nullable();
            $table->json("data_json")->nullable();
            $table->integer("order")->nullable();
            $table->boolean("is_active")->default(0);
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
        Schema::drop('constant_variable_model');
    }
}
