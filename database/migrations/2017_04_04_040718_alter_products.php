<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("products",function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropForeign(['profile_type_id']);
            $table->dropColumn('profile_type_id');
            $table->dropColumn('user_id');
            
            $table->integer("company_id")->unsigned();
            $table->foreign("company_id")->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products',function(Blueprint $table){
            $table->dropColumn(['company_id']);
            $table->integer("user_id")->unsigned()->nullable();
            $table->integer("profile_type_id")->unsigned()->nullable();
        });
    }
}
