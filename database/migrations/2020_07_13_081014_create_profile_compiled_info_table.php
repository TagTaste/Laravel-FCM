<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileCompiledInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_compiled_info', function(Blueprint $table){
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->integer('shoutout_post')->unsigned()->default(0);
            $table->integer('shoutout_shared_post')->unsigned()->default(0);
            $table->integer('collaborate_post')->unsigned()->default(0);
            $table->integer('collaborate_share_post')->unsigned()->default(0);
            $table->integer('photo_post')->unsigned()->default(0);
            $table->integer('photo_share_post')->unsigned()->default(0);
            $table->integer('poll_post')->unsigned()->default(0);
            $table->integer('poll_share_post')->unsigned()->default(0);
            $table->integer('product_share_post')->unsigned()->default(0);
            $table->integer('follower_count')->unsigned()->default(0);
            $table->integer('private_review_count')->unsigned()->default(0);
            $table->integer('public_review_count')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profile_compiled_info');
    }
}
