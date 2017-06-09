<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborationAddPrivacy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->integer("privacy_id")->unsigned()->nullable();
            $table->foreign('privacy_id')->references('id')->on('privacies');
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
            $table->dropColumn('privacy_id');
        });
    }
}
