<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssetsOrderVideosMetaToPublicReviewProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_products', function (Blueprint $table) {
            $table->json('videos_meta')->after('video_link')->nullable(); 
            $table->string('assets_order')->after('videos_meta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_review_products', function(Blueprint $table){
            $table->dropColumn(['videos_meta', 'assets_order']);
        });
    }
}
