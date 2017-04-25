<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPhotosAddPrivacy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos',function(Blueprint $table){
            $table->integer("privacy_id")->unsigned()->default(1);
            
            $table->foreign("privacy_id")->references('id')->on('privacies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photos',function(Blueprint $table){
            $table->dropForeign('privacy_id');
            $table->dropColumn('privacy_id');
        });
    }
}
