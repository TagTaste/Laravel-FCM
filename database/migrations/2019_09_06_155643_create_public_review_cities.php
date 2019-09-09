<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_review_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('state_code', 20)->nullable();
            $table->string('country', 50);
            $table->string('country_code', 20)->nullable();
            $table->string('latitude', 30);
            $table->string('longitude', 30);
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'city', 'state', 'is_active']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_review_cities');
        $table->dropIndex(['id', 'city', 'state', 'is_active']);

    }
}
