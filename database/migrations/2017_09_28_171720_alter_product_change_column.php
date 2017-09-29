<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->text("description")->nullable();
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
            $table->dropColumn("description");
        });
    }
}
