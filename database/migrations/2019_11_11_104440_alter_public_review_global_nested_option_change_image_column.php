<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicReviewGlobalNestedOptionChangeImageColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('public_review_global_nested_option', function(Blueprint $table){
            $table->text('image_url')->nullable()->change();
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
        Schema::table('public_review_global_nested_option', function(Blueprint $table){
            $table->string('image_url')->nullable()->change();
        });
    }
}
