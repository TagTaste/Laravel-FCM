<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserGroupTableAddGroupType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('user_group', function (Blueprint $table) {
            //
            $table->integer("group_type")->default(1);
            
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
        Schema::table('user_group', function (Blueprint $table) {
            //
            $table->dropColumn("group_type");
        });
    }
}
