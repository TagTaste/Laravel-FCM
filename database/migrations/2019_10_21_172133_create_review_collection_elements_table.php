<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewCollectionElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_collection_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 50)->nullable()->default(null);
            $table->unsignedInteger('collection_id')->references("id")->on("review_collections");
            $table->string('data_type', 50)->nullable()->default(null);
            $table->string('data_id', 100)->nullable()->default(null);
            $table->unsignedInteger('filter_id')->nullable()->default(null);
            $table->string('filter_name', 100)->nullable()->default(null);
            $table->text('filter_image')->nullable()->default(null);
            $table->string('filter_on', 50)->nullable()->default(null);
            $table->text('filter')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_collection_elements');
    }
}
