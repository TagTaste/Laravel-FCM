<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileSocialConnectAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("profiles",function(Blueprint $table){
            $table->boolean('facebook_connect')->default(0);
            $table->boolean('google_connect')->default(0);
            $table->boolean('linkedin_connect')->default(0);
            $table->string('google_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("profiles",function(Blueprint $table){
            $table->dropColumn('facebook_connect');
            $table->dropColumn('google_connect');
            $table->dropColumn('linkedin_connect');
            $table->dropColumn('google_url');
        });
    }
}
