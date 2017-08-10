<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaboratorsMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborators',function(Blueprint $table){
            $table->text('message')->nullable();
            $table->dateTime("archived_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("collaborators",function(Blueprint $table){
            $table->dropColumn('message');
            $table->dropColumn('archived_at');
        });
    }
}
