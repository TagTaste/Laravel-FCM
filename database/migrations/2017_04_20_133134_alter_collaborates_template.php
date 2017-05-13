<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaboratesTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("collaborates",function(Blueprint $table){
            $table->integer("template_id")->unsigned();
            
            $table->foreign("template_id")->references('id')->on('templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("collaborates",function(Blueprint $table){
            $table->dropForeign('template_id');
            $table->dropColumn('template_id');
        });
    }
}
