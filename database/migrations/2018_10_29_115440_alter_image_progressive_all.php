<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterImageProgressiveAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles',function(Blueprint $table){
            $table->json("image_meta")->nullable();
            $table->json("hero_image_meta")->nullable();
        });
        Schema::table('companies',function(Blueprint $table){
            $table->json("logo_meta")->nullable();
            $table->json("hero_image_meta")->nullable();
        });
        Schema::table('collaborates',function(Blueprint $table){
            $table->json("images_meta")->nullable();
        });
        Schema::table('photos',function(Blueprint $table){
            $table->json("image_meta")->nullable();
        });
        Schema::table('company_galleries',function(Blueprint $table){
            $table->json("image_meta")->nullable();
        });
        Schema::table('products',function(Blueprint $table){
            $table->json("image_meta")->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles',function(Blueprint $table){
            $table->dropColumn(["image_meta","hero_image_meta"]);
        });
        Schema::table('companies',function(Blueprint $table){
            $table->dropColumn(["logo_meta","hero_image_meta"]);
        });
        Schema::table('collaborates',function(Blueprint $table){
            $table->dropColumn(["images_meta"]);
        });
        Schema::table('photos',function(Blueprint $table){
            $table->dropColumn(["image_meta"]);
        });
        Schema::table('company_galleries',function(Blueprint $table){
            $table->dropColumn(["image_meta"]);
        });
        Schema::table('products',function(Blueprint $table){
            $table->dropColumn(["image_meta"]);
        });
    }
}
