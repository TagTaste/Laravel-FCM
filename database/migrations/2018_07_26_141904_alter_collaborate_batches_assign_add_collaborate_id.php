<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateBatchesAssignAddCollaborateId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('collaborate_batches_assign',function(Blueprint $table){
            $table->unsignedInteger('collaborate_id')->nullable();
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_batches_assign',function(Blueprint $table){
            $table->dropForeign('collaborate_batches_assign_collaborate_id_foreign');
            $table->dropColumn("collaborate_id");
        });
    }
}
