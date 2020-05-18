<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollabBatchAssignAddBillVerified extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_batches_assign', function(Blueprint $table){
            $table->integer('bill_verified')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_batches_assign', function(Blueprint $table){
            $table->dropColumn(['bill_verified']);
        });
    }
}
