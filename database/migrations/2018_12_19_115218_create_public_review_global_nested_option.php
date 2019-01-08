<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewGlobalNestedOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('public_review_global_nested_option',function(Blueprint $table){
            $table->increments('id');
            $table->string('type')->nullable();
            $table->integer('s_no');
            $table->integer('parent_id')->nullable();
            $table->string('value')->nullable();
            $table->boolean('is_active');
            $table->text('description')->nullable();
            $table->timestamps();
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
        Schema::drop('public_review_global_nested_option');
    }
}
