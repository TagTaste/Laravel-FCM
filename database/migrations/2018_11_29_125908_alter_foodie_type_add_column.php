<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFoodieTypeAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foodie_type', function (Blueprint $table) {
            $table->integer('order')->nullable();
            $table->string('technical_name')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foodie_type', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropColumn('technical_name');
        });
    }
}
