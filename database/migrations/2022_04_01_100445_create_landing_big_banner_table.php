<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLandingBigBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('landing_banner', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_id')->unsigned()->nullable();
            $table->string('model_name', 50);	
            $table->string('link')->nullable();
            $table->json('filter_meta')->nullable();            
            $table->json('images_meta')->nullable();            
            $table->string('value',50)->nullable();
            $table->string('banner_type',20)->nullable(false);
            $table->boolean('is_active')->default(0);
            $table->integer('updated_by')->unsigned();
            $table->timestamp("expires_on")->nullable();
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
        Schema::drop('landing_banner');
    }
}
