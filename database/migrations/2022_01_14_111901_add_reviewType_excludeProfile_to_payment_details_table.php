<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewTypeExcludeProfileToPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('payment_details', function (Blueprint $table) {
            //
            $table->integer('review_type')->nullable();
            $table->text('excluded_profiles')->nullable();
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
        Schema::table('payment_details', function (Blueprint $table) {
            //
            $table->dropColumn('review_type');
            $table->dropColumn('excluded_profiles');
            
        });
    }
}
