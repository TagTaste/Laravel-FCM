<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborationTemplateFieldsParent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("collaboration_template_fields",function(Blueprint $table){
            $table->integer("parent_field_id")->unsigned()->nullable();
            $table->float("order")->nullable();
            $table->foreign('parent_field_id')->references('id')->on('fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("collaboration_template_fields",function(Blueprint $table){
            $table->dropColumn("order");
            $table->dropColumn(['parent_field_id']);
        });
    }
}
