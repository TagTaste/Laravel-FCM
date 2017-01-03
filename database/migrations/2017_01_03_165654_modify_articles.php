<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles',function($table){
           $table->dropForeign(['author_id']);
           $table->dropColumn('author_id');

           $table->integer('user_id')->unsigned();
           $table->foreign('user_id')->references('id')->on('users');

            $table->integer('profile_type_id')->unsigned();
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
        Schema::table('articles',function($table){
            $table->integer('author_id')->unsigned()->nullable();
            $table->foreign("author_id")->references("id")->on("profiles");

            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id']);
            $table->dropForeign(['profile_type_id']);
            $table->dropColumn(['profile_type_id']);

        });
    }
}
