<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductCataloguesForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_catalogues',function(Blueprint $table){
            $table->dropForeign('product_catalogues_company_id_foreign');
            $table->foreign("company_id")->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("product_catalogues",function(Blueprint $table){
            $table->dropForeign('product_catalogues_company_id_foreign');
            $table->foreign("company_id")->references('id')->on('product_catalogues')->onDelete('cascade');
        });
    
    }
}
