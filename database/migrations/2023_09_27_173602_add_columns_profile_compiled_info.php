<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsProfileCompiledInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('profile_compiled_info', function (Blueprint $table){
            $table->integer('quiz_answer_count')->unsigned()->default(0);
            $table->integer('poll_answer_count')->unsigned()->default(0);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('profile_compiled_info', function (Blueprint $table){
            $table->dropColumn('quiz_answer_count');
            $table->dropColumn('poll_answer_count');
        });
    }
}
