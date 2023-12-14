<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTdsToPaymentLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('payment_links', function (Blueprint $table){
            $table->decimal('payout_amount', 13, 2)->after('amount');
            $table->decimal('tds_amount', 13, 2)->after('payout_amount')->default(0);
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
        Schema::table('payment_links', function (Blueprint $table){
            $table->dropColumn('payout_amount');
            $table->dropColumn('tds_amount');
        });
    }
}