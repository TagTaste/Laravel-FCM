<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyPhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_photos',function(Blueprint $table){
            $table->integer('photo_id')->unsigned();
            $table->integer("company_id")->unsigned();
            
            $table->foreign("photo_id")->references('id')->on('photos');
            $table->foreign("company_id")->references('id')->on('companies');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_photos');
    }
}
