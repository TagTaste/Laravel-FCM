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
            
            $table->string('brand_name', 255)->nullable()->change();
            $table->string('company_name', 255)->nullable()->change();  
            
            $table->index(['type_id', 'brand_id', 'city_id', 'is_pan']);
           
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

            $table->dropIndex(['type_id', 'brand_id', 'city_id', 'is_pan']);
        });
    }
}
