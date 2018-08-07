<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateBatchesChangeAllergens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('collaborate_batches',function(Blueprint $table){
//            $table->text("allergens")->nullable()->change();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('collaborate_batches',function(Blueprint $table){
//            $table->json('allergens')->nullable()->change();
//        });
    }
}
