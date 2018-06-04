<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateCategoriesAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_categories',function(Blueprint $table){
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->text("description")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_categories',function(Blueprint $table){
            $table->integer('parent_id')->unsigned()->nullable();
            $table->unique(array('name', 'parent_id'));
            $table->foreign('parent_id')->references('id')->on('collaborate_categories');
            $table->dropColumn("description");
        });
    }
}
