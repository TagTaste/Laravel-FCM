<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaboratesAddConstest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborates', function(Blueprint $table){
            $table->boolean('is_contest')->default(0);
            $table->integer('max_submissions')->default(3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborates', function(Blueprint $table){
            $table->dropColumn(['is_contest','max_submissions']);
        });
    }
}
