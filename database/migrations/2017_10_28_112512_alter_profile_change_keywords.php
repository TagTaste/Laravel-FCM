<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileChangeKeywords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles',function(Blueprint $table){
            $table->text("keywords")->nullable()->change();
            $table->text("expertise")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles',function(Blueprint $table){
            $table->string('keywords')->nullable()->change();
            $table->string('expertise')->nullable()->change();
        });
    }
}
