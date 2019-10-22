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
            $table->unsignedInteger('collection_id')->unsigned()->nullable()->default(null);
            $table->string('collection_type', 50);
            $table->string('data_type', 50);
            $table->unsignedInteger('data_id')->nullable()->default(null);
            $table->unsignedInteger('filter_id')->nullable()->default(null);
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
