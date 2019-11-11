<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCollaborateApplicants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborate_applicants', function(Blueprint $table){
            $table->boolean('terms_verified')->default(0);
            $table->json('document_meta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_applicants', function(Blueprint $table){
            $table->dropColumn(['terms_verified','document_meta']);
        });
    }
}
