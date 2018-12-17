<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGlobalNestedOptionAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_nested_option', function (Blueprint $table) {
            $table->boolean('is_intensity')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('global_nested_option', function (Blueprint $table) {
            $table->dropColumn('is_intensity');
        });
    }
}
