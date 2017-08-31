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
            $table->tinyInteger("phone_private")->default(1);
            $table->tinyInteger("address_private")->default(1);
            $table->tinyInteger("email_private")->default(1);

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
            $table->dropColumn("phone_private");
            $table->dropColumn("address_private");
            $table->dropColumn("email_private");
        });
    }
}
