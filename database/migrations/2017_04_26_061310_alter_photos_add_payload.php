<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPhotosAddPayload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos',function(Blueprint $table){
            $table->integer('payload_id')->unsigned()->nullable();
            $table->foreign("payload_id")->references("id")->on('channel_payloads');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("photos",function(Blueprint $table){
            $table->dropForeign('photos_payload_id_foreign');
            $table->dropColumn('payload_id');
        });
    }
}
