<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PublicReviewDeprecation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('public_review_products', function (Blueprint $table) {
            //
            $table->boolean('not_accepting_response')->default(false);
            $table->longText('admin_note')->nullable();
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
        Schema::table('public_review_products', function (Blueprint $table) {
            //
            $table->dropColumn('not_accepting_response');
            $table->dropColumn('admin_note');
            
        });
    }
}
