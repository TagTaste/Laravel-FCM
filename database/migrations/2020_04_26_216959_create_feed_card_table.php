<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_card', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data_type', 50)->nullable()->default(null);
            $table->integer('data_id')->unsigned();
            $table->string('title', 255)->nullable()->default(null);
            $table->string('subtitle', 255)->nullable()->default(null);
            $table->string('name', 140)->nullable()->default(null);
            $table->text('image')->nullable()->default(null);
            $table->string('description', 1500)->nullable()->default(null);
            $table->text('icon')->nullable()->default(null);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('feed_card');
    }
}