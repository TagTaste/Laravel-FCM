<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicProductUserReviewAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_product_user_review',function($table){
            $table->tinyInteger("source")->default(0);
            $table->integer("outlet_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_product_user_review',function($table){
            $table->dropColumn(['source','outlet_id']);
        });
    }
}
