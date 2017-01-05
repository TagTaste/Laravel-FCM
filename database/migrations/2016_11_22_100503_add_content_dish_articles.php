<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentDishArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dish_articles',function($table){
            $table->text('description')->after('id');
            $table->text('ingredients')->after('description');
            $table->string('image')->nullable()->after('ingredients');
            $table->string('category')->after('image');
            $table->string('serving')->after('category');
            $table->string('calorie')->after('serving');
            $table->string('time')->after('calorie');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dish_articles',function($table){
            $table->dropColumn('description');
            $table->dropColumn('ingredients');
            $table->dropColumn('image');
            $table->dropColumn('category');
            $table->dropColumn('serving');
            $table->dropColumn('calorie');
            $table->dropColumn('time');
        });
    }
}
