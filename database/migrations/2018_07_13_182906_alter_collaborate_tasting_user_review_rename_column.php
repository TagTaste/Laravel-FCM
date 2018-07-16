<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateTastingUserReviewRenameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_tasting_user_review',function(Blueprint $table){
            $table->dropColumn(['aromatic_id']);
            $table->renameColumn("aroma_id","leaf_id");
            $table->string("intensity")->nullable();
            $table->integer("current_status")->default(0); // 0 means begin 1 means in process 2 means completed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_tasting_user_review',function(Blueprint $table){
            $table->unsignedInteger("aromatic_id")->nullable();
            $table->renameColumn("leaf_id","aroma_id");
            $table->dropColumn(["intensity","current_status"]);
        });
    }
}
