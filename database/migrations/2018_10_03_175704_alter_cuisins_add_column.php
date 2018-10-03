<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCuisinsAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cuisines',function(Blueprint $table){
            $table->boolean("is_active")->default(1);
            $table->boolean("country")->nullable();
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
        Schema::table('cuisines',function(Blueprint $table){
            $table->dropColumn(["is_active","country","description"]);

        });
    }
}
