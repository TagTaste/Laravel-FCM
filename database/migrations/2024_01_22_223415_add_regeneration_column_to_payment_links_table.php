<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegenerationColumnToPaymentLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('payment_links', function (Blueprint $table) {
            $table->boolean('in_regeneration')->nullable()->default(0);            
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
        Schema::table('payment_links', function(Blueprint $table){
            $table->dropColumn('in_regeneration');
        });
    }
}
