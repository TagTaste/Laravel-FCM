<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("advertisements",function(Blueprint $table){
            $table->increments('id');
            $table->string('title')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->string('video')->nullable()->default(null);
            $table->string('youtube_url')->nullable()->default(null);
            $table->text('link')->nullable()->default(null);
            $table->text('image')->nullable()->default(null);
            $table->text('cities')->nullable()->default(null);
            $table->text('payload')->nullable()->default(null);
            $table->text('model')->nullable()->default(null);
            $table->integer('model_id')->nullable()->default(null);
            $table->integer('company_id')->unsigned()->nullable()->default(null);
            $table->integer('profile_id')->unsigned()->nullable()->default(null);
            $table->boolean('is_expired')->default(0);
            $table->dateTime('expired_at')->nullable()->default(null);
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
        Schema::drop('advertisements');
    }
}
