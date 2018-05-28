<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShoutoutAddMediaJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE `shoutouts` MODIFY `content` TEXT NULL;');

        Schema::table('shoutouts',function(Blueprint $table){
            $table->text('cloudfront_media_url')->nullable();
            $table->text('media_url')->nullable();
            $table->json('media_json')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE `shoutouts` MODIFY `content` TEXT NOT NULL;');

        Schema::table('shoutouts',function(Blueprint $table){
            $table->dropColumn(['media_url']);
            $table->dropColumn(['media_json']);
            $table->dropColumn(['cloudfront_media_url']);
            });
    }
}
