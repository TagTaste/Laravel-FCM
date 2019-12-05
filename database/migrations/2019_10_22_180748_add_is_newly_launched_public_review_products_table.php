<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsNewlyLaunchedPublicReviewProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_products', function (Blueprint $table) {
            $table->integer('is_newly_launched')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_review_products', function (Blueprint $table) {
            $table->dropColumn(['is_newly_launched']);
        });
    }
}
