<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewProductCompanies extends Migration
{
    /**
     * Run the migrations.
     *  
     * @return void
     */
    public function up()
    {
        Schema::create('public_review_product_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'name', 'is_active']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_review_product_companies');
        $table->dropIndex(['id', 'name', 'is_active']);

    }
}
