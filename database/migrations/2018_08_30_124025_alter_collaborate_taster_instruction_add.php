<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateTasterInstructionAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->text("taster_instruction")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->dropColumn("taster_instruction");

        });
    }
}
