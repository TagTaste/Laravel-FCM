<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateTastingUserReviewAddProfileidColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_tasting_user_review',function(Blueprint $table){
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references("id")->on("profiles");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_tasting_user_review',function(Blueprint $table){
            $table->dropColumn(['profile_id']);
        });
    }
}
