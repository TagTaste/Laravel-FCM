<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInviteAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invites',function(Blueprint $table){
            $table->integer('profile_id')->unsigned();
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->string("source")->nullable();
            $table->integer("state")->nullable();
            $table->string("mail_code")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invites',function(Blueprint $table){
            $table->dropForeign('invites_profile_id_foreign');
            $table->dropColumn('profile_id');
            $table->dropColumn('source');
            $table->dropColumn('state');
            $table->dropColumn("mail_code");
        });
    }
}
