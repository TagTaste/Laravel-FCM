<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicGlobalOptionAddParent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_review_nested_options', function(Blueprint $table){
                $table->integer('parent_sequence_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_review_nested_options', function(Blueprint $table){
            $table->dropColumn(['parent_sequence_id']);
        });
    }
}
