<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateImagesJsonAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->json('images')->nullable();
            $table->dropColumn(['image1','image2','image3','image4','image5']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->dropColumn(['images']);
            $table->string("image1")->nullable();
            $table->string("image2")->nullable();
            $table->string("image3")->nullable();
            $table->string("image4")->nullable();
            $table->string("image5")->nullable();
        });
    }
}
