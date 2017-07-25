<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileTvshowChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("profile_shows",function(Blueprint $table){
            $table->renameColumn('start_date', 'date');
            $table->dropColumn('end_date');
            $table->dropColumn('current');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_shows',function(Blueprint $table){
            $table->renameColumn('date', 'start_date');
            $table->date('end_date')->nullable();
            $table->boolean('current')->default('0');
        });
    }
}
