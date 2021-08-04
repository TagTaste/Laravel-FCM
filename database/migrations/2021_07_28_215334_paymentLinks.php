<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_links', function (Blueprint $table) {
            $table->increments("id");
            $table->string("transaction_id");
            $table->unique("transaction_id");
            $table->decimal("amount", 13, 2);
            $table->unsignedInteger("payment_id");
            $table->foreign("payment_id")->references("id")->on("payment_details");
            $table->string("payout_link_id")->nullable();
            $table->string("link")->nullable();
            $table->boolean("is_active")->default(1);
            $table->string("status_id");
            $table->timestamp("expired_at")->nullable();
            $table->string("comments")->nullable();
            $table->json("status_json")->nullable();            
            $table->bigInteger("phone");
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->string("model_id");
            $table->integer("sub_model_id")->nullable();
            $table->string("model_type");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_links');
    }
}
