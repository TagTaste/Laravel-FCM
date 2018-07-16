<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateTastingAromaQuestionParentIdColumnNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_tasting_aroma_question',function(Blueprint $table){
            $table->boolean("nested_option")->default(0);
            $table->unsignedInteger('header_type_id')->nullable();
            $table->integer("parent_id")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_tasting_aroma_question',function(Blueprint $table){
            $table->dropColumn(['header_type_id','nested_option']);
            $table->integer("parent_id")->change();
        });
    }
}
