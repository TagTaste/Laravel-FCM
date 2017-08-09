<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaboratorsMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborators',function(Blueprint $table){
            $table->text('message')->nullable();
            $table->boolean("is_shortlist")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("collaborators",function(Blueprint $table){
            $table->dropColumn('message');
            $table->dropColumn('is_shortlist');
        });
    }
}
