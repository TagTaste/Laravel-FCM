<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateReviewAddAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_tasting_user_review', function(Blueprint $table) {
            $table->integer('address_map_id')->unsigned()->nullable();
            $table->foreign('address_map_id')->references('address_id')->on('collaborate_addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_tasting_user_review', function(Blueprint $table) {
            $table->dropForeign(['address_map_id']);
            $table->dropColumn(['address_map_id']);
        });
    }
}
