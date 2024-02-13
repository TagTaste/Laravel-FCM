<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDonationToPaymentLinks extends Migration
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
            $table->unsignedInteger('donation_organisation_id')->nullable();

            $table->foreign("donation_organisation_id")->references("id")->on("donation_organisations");

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
            $table->dropColumn('donation_organisation_id');
        });
    }
}
