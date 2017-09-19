<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyProductAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("products",function(Blueprint $table){
            $table->string('moq')->nullable()->change();
            $table->renameColumn('about', 'description');
            $table->text("delivery_cities")->nullable();
            $table->boolean("certifications")->default(0)->change();
            $table->string("category")->nullable()->change();
            $table->float("price")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("products",function(Blueprint $table){
            $table->float('moq')->nullable()->change();
            $table->renameColumn('description', 'about');
            $table->dropColumn("delivery_cities");
            $table->text("certifications")->nullable()->change();
            $table->string("category");
            $table->float("price");
        });
    }
}
