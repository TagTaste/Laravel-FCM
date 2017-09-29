<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductChangeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("products",function(Blueprint $table){
            $table->string("price")->nullable()->change();
            $table->text("description")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("products",function(Blueprint $table){
            $table->double("price")->nullable()->change();
            $table->string("description")->nullable()->change();
        });
    }
}
