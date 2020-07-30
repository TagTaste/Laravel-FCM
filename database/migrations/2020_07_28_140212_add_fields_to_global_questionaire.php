<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToGlobalQuestionaire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_questions', function(Blueprint $table){
            $table->boolean('is_benchmark')->default(false);
            $table->boolean('is_private')->default(false);
            $table->integer("state")->default(0);
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('global_questions', function(Blueprint $table){
            $table->dropColumn("is_benchmark");
            $table->dropColumn("is_private");
            $table->dropColumn("state");
            $table->dropColumn("created_by");
            $table->dropColumn("updated_by");
            $table->dropColumn("published_at");
            $table->dropColumn("deleted_at");
        });
    }
}
