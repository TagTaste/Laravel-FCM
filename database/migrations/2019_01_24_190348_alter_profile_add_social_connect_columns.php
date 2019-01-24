<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileAddSocialConnectColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles',function($table){
            $table->boolean('is_facebook_connected')->default(0);
            $table->boolean('is_google_connected')->default(0);
            $table->boolean('is_linkedin_connected')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles',function($table){
            $table->dropColumn(['is_facebook_connected','is_linkedin_connected','is_google_connected']);
        });
    }
}
