<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPosToCollaborateTastingNestedOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('collaborate_tasting_nested_options', function (Blueprint $table){
            $table->double('pos', 32, 16)->after('value')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('collaborate_tasting_nested_options', function (Blueprint $table){
            $table->dropColumn('pos');
        });
    }
}
