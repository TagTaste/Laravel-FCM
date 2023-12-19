<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSectionTitleToCollaborateTastingEntryMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('collaborate_tasting_entry_mapping', function (Blueprint $table){
            $table->string('header_title')->after('header_id')->nullable();
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
        Schema::table('collaborate_tasting_entry_mapping', function (Blueprint $table){
            $table->dropColumn('header_title');
        });
    }
}
