<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeShareLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_share_likes', function (Blueprint $table) {
             $table->unsignedInteger('recipe_share_id');
            $table->unsignedInteger('profile_id');
             $table->foreign("profile_id")->references('id')->on('profiles')->onDelete('cascade');
              $table->foreign("recipe_share_id")->references('id')->on('recipe_shares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_share_likes');
    }
}
