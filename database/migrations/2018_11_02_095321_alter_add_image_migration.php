<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddImageMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('specializations',function(Blueprint $table){
            $table->string("image")->nullable();
        });
        Schema::table('occupations',function(Blueprint $table){
            $table->string("image")->nullable();
        });
        Schema::table('allergens',function(Blueprint $table){
            $table->string("image")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specializations',function(Blueprint $table){
            $table->dropColumn(["image"]);

        });
        Schema::table('occupations',function(Blueprint $table){
            $table->dropColumn(["image"]);

        });
        Schema::table('allergens',function(Blueprint $table){
            $table->dropColumn(["image"]);

        });
    }
}
