<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRecipesVarcharInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("recipes",function(Blueprint $table){
           $table->smallInteger('preparation_time')->nullable()->change();
           $table->smallInteger('cooking_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("recipes",function(Blueprint $table){
            $table->string('preparation_time')->change();
            $table->string('cooking_time')->change();
        });
    }
}
