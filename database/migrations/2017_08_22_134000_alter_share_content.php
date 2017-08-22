<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShareContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("shoutout_shares",function(Blueprint $table){
            $table->text('content')->nullable();

        });
        Schema::table("recipe_shares",function(Blueprint $table){
            $table->text('content')->nullable();

        });
        Schema::table("collaborate_shares",function(Blueprint $table){
            $table->text('content')->nullable();

        });
        Schema::table("photo_shares",function(Blueprint $table){
            $table->text('content')->nullable();

        });
        Schema::table("job_shares",function(Blueprint $table){
            $table->text('content')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("shoutout_shares",function(Blueprint $table){
            $table->dropColumn("content");
        });
        Schema::table("recipe_shares",function(Blueprint $table){
            $table->dropColumn("content");
        });
        Schema::table("collaborate_shares",function(Blueprint $table){
            $table->dropColumn("content");
        });
        Schema::table("photo_shares",function(Blueprint $table){
            $table->dropColumn("content");
        });
        Schema::table("job_shares",function(Blueprint $table){
            $table->dropColumn("content");
        });
    }
}
