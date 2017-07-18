<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            //create
            $table->integer("cuisine_id")->unsigned();
            $table->tinyInteger("type")->unsigned();
            $table->text("directions")->nullable();
    
            $table->foreign('cuisine_id')->references('id')->on('cuisines')->onDelete('cascade');
    
            //change
            $table->text('description')->nullable()->change();
            $table->string('serving')->nullable()->change();
            
            //drop
            $table->dropColumn('ingredients');
            $table->dropColumn('content');
            $table->dropColumn('calorie');
            $table->dropColumn('category');
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
            //drop new columns
            $table->dropColumn('cuisine_id');
            $table->dropColumn('type');
            $table->dropColumn("directions");
            
            //revert
            $table->text('description')->change();
            $table->string('serving')->change();
            
            //recreate dropped columns
            $table->text("ingredients");
            $table->text("content");
            $table->string("calorie");
            $table->string("category");
            $table->string("image")->nullable();
            $table->boolean("hasRecipe")->default(0);
            $table->boolean("showcase")->default(0);

        });
    }
}
