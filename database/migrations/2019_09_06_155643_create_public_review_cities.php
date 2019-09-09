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
            $table->string('city');
            $table->string('state');
            $table->string('state_code')->nullable();
            $table->string('country');
            $table->string('country_code')->nullable();
            $table->string('latitude');
            $table->string('longitude');
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
        Schema::dropIfExists('public_review_cities');
    }
}
