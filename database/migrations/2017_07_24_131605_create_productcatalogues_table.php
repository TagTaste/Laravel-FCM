<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCataloguesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_catalogues', function(Blueprint $table) {
            $table->increments('id');
            $table->string('product');
            $table->string('category');
            $table->integer('company_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign("company_id")->references('id')->on('product_catalogues')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_catalogues');
	}

}
