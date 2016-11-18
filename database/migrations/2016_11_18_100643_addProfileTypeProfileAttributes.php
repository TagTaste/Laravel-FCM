<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileTypeProfileAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_attributes',function($table){
            $table->integer('profile_type_id')->unsigned()->nullable();

            $table->foreign('profile_type_id')->references('id')->on('profile_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_attributes',function($table){
            $table->dropForeign(['profile_type_id']);
            $table->dropColumn('profile_type_id');
        });
    }
}
