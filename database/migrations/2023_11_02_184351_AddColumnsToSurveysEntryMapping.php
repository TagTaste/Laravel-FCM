<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSurveysEntryMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('surveys_entry_mapping', function (Blueprint $table) {
            $table->string('section_id')->after('surveys_attempt_id')->nullable();
            $table->integer('activity')->after('section_id')->nullable();
            
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
        Schema::table('surveys_entry_mapping', function(Blueprint $table){
            $table->dropColumn(['section_id', 'activity']);
        });
    }
}
