<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentsRecipeShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('comments_recipe_shares',function(Blueprint $table){
            $table->unsignedInteger('recipe_share_id');
            $table->unsignedInteger('comment_id');
            $table->foreign("comment_id")->references('id')->on('comments');
              $table->foreign("recipe_share_id")->references('id')->on('recipe_shares');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_recipe_shares');
    }
}
