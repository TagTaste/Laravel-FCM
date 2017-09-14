<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyRatingReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("company_ratings",function(Blueprint $table){
            $table->text("review")->nullable();
            $table->string("title")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("company_ratings",function(Blueprint $table){
            $table->dropColumn("review");
            $table->dropColumn("title");
        });
    }
}
