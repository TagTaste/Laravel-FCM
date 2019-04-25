<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicReviewProductColumnNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_products', function(Blueprint $table){
            $table->json('brand_logo')->nullable()->change();
            $table->json('company_logo')->nullable()->change();
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
            $table->json('brand_logo')->change();
            $table->json('company_logo')->change();
        });
    }
}
