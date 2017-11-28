<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVersionsDoubleString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("versions",function(Blueprint $table){
            $table->string('compatible_version')->change();
            $table->string('latest_version')->change();
        });
        
        Schema::rename("versions","apk_versions");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("apk_versions",function(Blueprint $table){
            $table->decimal('compatible_version',6,3)->change();
            $table->decimal('latest_version',6,3)->change();
        });
        
        Schema::rename("apk_versions","versions");
    }
}
