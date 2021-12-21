<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE `payment_links` ADD `payment_channel` VARCHAR(50) NULL DEFAULT NULL AFTER `model_type`, ADD INDEX (`payment_channel`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE `payment_links`
        DROP `payment_channel`;');
                //
    }
}
