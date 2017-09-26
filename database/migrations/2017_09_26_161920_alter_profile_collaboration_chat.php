<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileCollaborationChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("profiles",function(Blueprint $table){
            $table->smallInteger("dob_private")->default(3)->change();
            $table->smallInteger("phone_private")->default(3)->change();
            $table->smallInteger("address_private")->default(3)->change();
            $table->smallInteger("email_private")->default(3)->change();
        });

        Schema::table("chat_messages",function(Blueprint $table){
            $table->text('message')->nullable()->change();

        });

        Schema::table("collaborates",function(Blueprint $table){
            $table->text('eligibility_criteria')->nullable()->change();
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
            $table->tinyInteger("dob_private")->default(1)->change();
            $table->tinyInteger("phone_private")->default(1)->change();
            $table->tinyInteger("address_private")->default(1)->change();
            $table->tinyInteger("email_private")->default(1)->change();
        });

        Schema::table("chat_messages",function(Blueprint $table){
            $table->string('message')->nullable()->change();
        });

        Schema::table("collaborates",function(Blueprint $table){
            $table->string('eligibility_criteria')->nullable()->change();
        });
    }
}
