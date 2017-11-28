<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSocialAccountsAccessToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('social_accounts','access_token')){
            Schema::table('social_accounts',function(Blueprint $table){
                $table->text("access_token")->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('social_accounts','access_token')){
            Schema::table('social_accounts',function(Blueprint $table){
                $table->dropColumn('access_token');
            });
        }
        
        
    }
}
