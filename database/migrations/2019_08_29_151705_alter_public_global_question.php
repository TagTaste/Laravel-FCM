<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicGlobalQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_global_questions', function(Blueprint $table){
           $table->boolean('is_active')->default(1);
           $table->dateTime('last_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_review_global_questions', function(Blueprint $table){
            $table->dropColumn(['is_active','last_updated']);
        });
    }
}
