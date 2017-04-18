<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRecipesAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->string("tutorial_link")->nullable();
            $table->string('calorie')->nullable()->change();
            $table->boolean('billable')->default(0);
            $table->integer('privacy_id')->unsigned();
            $table->foreign("privacy_id")->references('id')->on('privacies');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('tutorial_link');
            $table->dropColumn('billable');
            $table->dropForeign('privacy_id');
            $table->dropColumn('privacy_id');
            $table->string('calorie')->change();
        });
    }
}
