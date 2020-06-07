<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPalateDetailToProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('palate_visibility')->unsigned()->default(0);
            $table->integer('palate_iteration')->unsigned()->nullable()->default(0);
            $table->boolean('palate_iteration_status')->nullable()->default(1);
            $table->boolean('palate_test_status')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['palate_visibility']);
            $table->dropColumn(['palate_iteration']);
            $table->dropColumn(['palate_iteration_status']);
            $table->dropColumn(['palate_test_status']);
        });
    }
}