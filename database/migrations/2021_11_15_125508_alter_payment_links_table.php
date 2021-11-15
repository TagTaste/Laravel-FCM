<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentLinksTable extends Migration
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
            //
            $table->text("admin_note")->nullable();
            $table->string("parent_transaction_id")->nullable();
            $table->integer("transaction_generation_type")->default(1);
            //1-Automated, 2-Manual
            $table->unsignedInteger("payment_id")->nullable()->change();
            
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
        Schema::table('payment_links', function (Blueprint $table) {
            //
            $table->dropColumn("admin_note");
            $table->dropColumn("parent_transaction_id");
            $table->dropColumn("transaction_generation_type");
            $table->unsignedInteger("payment_id")->nullable(false)->change();
        });
    }
}
