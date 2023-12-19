<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicReviewEntryMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_review_entry_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->uuid('product_id');
            $table->integer('header_id')->unsigned()->nullable();
            $table->string('activity')->nullable();            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->foreign("header_id")->references("id")->on("public_review_question_headers");


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
        Schema::dropIfExists('public_review_entry_mapping');
    }
}
