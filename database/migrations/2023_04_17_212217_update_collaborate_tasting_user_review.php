<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCollaborateTastingUserReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('collaborate_tasting_user_review', function (Blueprint $table) {
            $table->integer('value_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('collaborate_tasting_user_review', function (Blueprint $table) {
            $table->unsignedInteger('value_id')->nullable()->change();
        });
    }
}
