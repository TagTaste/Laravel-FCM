<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRecipes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipes',function(Blueprint $table){
            $table->string('preparation_time');
            $table->string('cooking_time');
            $table->tinyInteger("level")->unsigned();
            $table->text('tags')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipes',function(Blueprint $table){
            $table->dropColumn('preparation_time');
            $table->dropColumn('cooking_time');
            $table->dropColumn("level");
            $table->dropColumn('tags');
        });
    }
}
