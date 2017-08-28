<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfilePrivacy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("profiles",function(Blueprint $table){
            $table->tinyInteger("phone_privacy")->default(1);
            $table->tinyInteger("address_privacy")->default(1);
            $table->tinyInteger("email_privacy")->default(1);

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
            $table->dropColumn("phone_privacy");
            $table->dropColumn("address_privacy");
            $table->dropColumn("email_privacy");
        });
    }
}
