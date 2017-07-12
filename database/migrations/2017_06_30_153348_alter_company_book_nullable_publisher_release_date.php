<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyBookNullablePublisherReleaseDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_books',function(Blueprint $table){
            $table->text('publisher')->nullable()->change();
            $table->date('release_date')->nullable()->change();        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_books',function(Blueprint $table){
            $table->text('publisher')->change();
            $table->date('release_date')->change();        });
    }
}
