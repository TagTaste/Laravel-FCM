<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200)->nullable()->default(null);
            $table->string('subtitle', 400)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->text('image')->nullable()->default(null);
            $table->string('type', 50)->nullable()->default(null);
            $table->string('category_type', 50)->nullable()->default(null);
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('review_collections');
    }
}
