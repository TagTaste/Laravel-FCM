<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotForSuggestionPublicReviewProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_products', function (Blueprint $table) {
            $table->boolean('is_suggestion_allowed')->default(1);
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
            $table->dropColumn(['is_suggestion_allowed']);
        });
    }
}
