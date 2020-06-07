<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPallateDetailToProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('pallate_visibility')->unsigned()->default(0);
            $table->integer('pallate_iteration')->unsigned()->nullable()->default(0);
            $table->boolean('pallate_iteration_status')->nullable()->default(1);
            $table->boolean('pallate_test_status')->nullable()->default(0);
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
            $table->dropColumn(['pallate_visibility']);
            $table->dropColumn(['pallate_iteration']);
            $table->dropColumn(['pallate_iteration_status']);
            $table->dropColumn(['pallate_test_status']);
        });
    }
}