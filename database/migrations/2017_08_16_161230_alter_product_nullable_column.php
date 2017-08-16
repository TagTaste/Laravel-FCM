<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductNullableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("products",function(Blueprint $table){
            $table->float('moq')->default(0)->change();
            $table->string('type')->nullable()->change();
            $table->string('about')->nullable()->change();
            $table->string('ingredients')->nullable()->change();
            $table->string('certifications')->nullable()->change();
            $table->string('portion_size')->nullable()->change();
            $table->string('shelf_life')->nullable()->change();
            $table->string('mode')->nullable()->change();
            $table->string("category");

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
            $table->float('moq');
            $table->string('type');
            $table->string('about');
            $table->string('ingredients');
            $table->string('certifications');
            $table->string('portion_size');
            $table->string('shelf_life');
            $table->string('mode');
            $table->dropColumn('category');

        });
    }
}
