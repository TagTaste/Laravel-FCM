<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSocialAccountsDropProfileType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_accounts',function(Blueprint $table){
            $table->dropForeign('social_accounts_profile_type_id_foreign');
            $table->dropColumn('profile_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('social_accounts',function(Blueprint $table){
            $table->integer('profile_type_id')->unsigned();
            $table->foreign('profile_type_id')->references('id')->on('profile_types');
        });
    }
}
