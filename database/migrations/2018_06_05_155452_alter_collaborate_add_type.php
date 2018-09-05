<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->integer('type_id')->unsigned()->nullable();
            $table->foreign('type_id')->references('id')->on('collaborate_types');
            $table->boolean('is_taster_residence')->default(0);
            $table->string('collaborate_type')->default('collaborate')->nullable();
            $table->text('allergens')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->dropForeign(['type_id']);
            $table->dropColumn(['type_id','is_taster_residence','collaborate_type','allergens']);
        });
    }
}
