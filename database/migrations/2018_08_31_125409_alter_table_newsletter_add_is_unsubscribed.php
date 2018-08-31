<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNewsletterAddIsUnsubscribed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('newsletters',function(Blueprint $table){
            $table->integer('is_unsubscribed')->default(0);
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
        Schema::table('newsletters',function(Blueprint $table){
            $table->dropColumn('is_unsubscribed');
        });
    }
}
