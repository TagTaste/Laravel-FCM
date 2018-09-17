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
//        Schema::table('collaborates',function(Blueprint $table){
//            $table->json('images')->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     *
     */
    public function down()
    {
//        Schema::table('collaborates',function(Blueprint $table){
//            $table->dropColumn(['images']);
//        });
    }
}
