<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayoutDetailToPaymentLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('payment_links', function(Blueprint $table){
            $table->json("payout_detail")->nullable();            
        });
    }

    /**s
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('payment_links', function(Blueprint $table){
            $table->dropColumn('payout_detail');
        });
    }
}
