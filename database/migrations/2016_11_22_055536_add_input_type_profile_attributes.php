<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInputTypeProfileAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("profile_attributes",function($table){
            $table->string("input_type")->nullable();
            $table->dropColumn("requires_upload");
            $table->dropColumn("multiline");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("profile_attributes",function($table){
            $table->dropColumn("input_type");
            $table->boolean('multiline')->default(0);
            $table->boolean('requires_upload')->default(0);
            
        });
    }
}
