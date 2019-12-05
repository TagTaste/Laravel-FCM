<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewCollectionFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('review_collection_filters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->string('filter_on', 255)->nullable()->default(null);
            $table->text('filter')->nullable()->default(null);
            $table->text('filter_image')->nullable()->default(null);
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
        //
        Schema::dropIfExists('review_collection_filters');
    }
}
