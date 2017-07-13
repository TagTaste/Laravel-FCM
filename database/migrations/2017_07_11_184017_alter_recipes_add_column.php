<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRecipesAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('recipes', function(Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->string('serving')->nullable()->change();
            $table->integer("cuisine_id")->unsigned();
            $table->tinyInteger("type")->unsigned();
            $table->text("directions")->nullable();
            $table->dropColumn('ingredients');
            $table->dropColumn('content');
            $table->dropColumn('calorie');
            $table->dropColumn('category');
            $table->dropColumn('billable');
            $table->dropColumn('image');
            $table->dropColumn("hasRecipe");
            $table->dropColumn("showcase");

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipes', function(Blueprint $table) {
            $table->dropColumn('cuisine_id');
            $table->dropColumn('type');
            $table->dropColumn("directions");
            $table->text("ingredients");
            $table->text("content");
            $table->text('description')->change();
            $table->string('serving')->change();
            $table->text("calorie")->unsigned();
            $table->text("category")->unsigned();
            $table->tinyInteger("billable")->unsigned();
            $table->text("image")->unsigned();
            $table->tinyInteger("hasRecipe")->unsigned();
            $table->tinyInteger("showcase")->unsigned();

        });
    }
}
