<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateTastingUserReviewAddProfileidColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborate_batches_color',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('collaborate_batches',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->text('notes')->nullable();
            $table->json('allergens')->nullable();
            $table->text('instruction')->nullable();
            $table->unsignedInteger('color_id');
            $table->foreign("color_id")->references("id")->on("collaborate_batches_color");
            $table->unsignedInteger('collaborate_id');
            $table->foreign("collaborate_id")->references("id")->on("collaborates");
            $table->unique(['name', 'collaborate_id']);
            $table->timestamps();


        });
        Schema::table('collaborate_tasting_user_review',function(Blueprint $table){
            $table->unsignedInteger('batch_id');
            $table->foreign("batch_id")->references("id")->on("collaborate_batches");
            $table->unsignedInteger('profile_id');
            $table->foreign("profile_id")->references("id")->on("profiles");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborate_tasting_user_review',function(Blueprint $table){
            $table->dropForeign('collaborate_tasting_user_review_batch_id_foreign');
            $table->dropForeign('collaborate_tasting_user_review_profile_id_foreign');
            $table->dropColumn(['profile_id','batch_id']);
        });
    }
}
