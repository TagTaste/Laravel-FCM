<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivacyIdToQuizShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_shares', function (Blueprint $table) {
            //
            $table->smallInteger('privacy_id')->nullable()->after("content");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_shares', function (Blueprint $table) {
            //
            $table->smallInteger('privacy_id')->nullable();
        });
    }
}
