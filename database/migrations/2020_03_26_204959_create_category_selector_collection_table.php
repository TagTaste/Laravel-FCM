<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorySelectorCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('category_selector_collection', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_type', 50)->nullable()->default(null);
            $table->integer('category_id')->unsigned()->default(0);
            $table->string('data_type', 50)->nullable()->default(null);
            $table->string('data_id', 100)->nullable()->default(null);
            $table->boolean('is_active')->default(1);
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
        //
        Schema::dropIfExists('category_selector_collection');
    }
}
