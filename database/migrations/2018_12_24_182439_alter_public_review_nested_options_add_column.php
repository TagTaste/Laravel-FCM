<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicReviewNestedOptionsAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_nested_options', function (Blueprint $table) {
            $table->boolean('is_intensity')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_review_nested_options', function (Blueprint $table) {
            $table->string('is_intensity');
        });
    }
}
