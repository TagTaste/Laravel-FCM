<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicReviewProductsAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_products', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            
            $table->integer('brand_id')->unsigned()->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned()->nullable();
            $table->boolean('is_pan')->default(0);
            
            $table->string('brand_name')->nullable()->change();
            $table->string('company_name')->nullable()->change();    
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_review_products', function (Blueprint $table) {
           
            $table->dropColumn(['brand_id']);
            $table->dropColumn(['city_id']);
            $table->dropColumn(['type_id']);
            $table->dropColumn(['is_pan']);

            $table->string('brand_name')->change();
            $table->string('company_name')->change(); 
        });
    }
}
