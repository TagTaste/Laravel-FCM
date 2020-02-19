<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReviewCollectionElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_collection_elements', function (Blueprint $table) {
            $table->string('title', 200)->nullable()->default(null);
            $table->string('subtitle', 400)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->text('image')->nullable()->default(null);
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
        Schema::table('review_collection_elements', function (Blueprint $table) {
            $table->dropColumn(['title']);
            $table->dropColumn(['subtitle']);
            $table->dropColumn(['description']);
            $table->dropColumn(['image']);
        });
    }
}
