<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableGlobalQuestionsAddConsistency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_questions', function(Blueprint $table) {
            $table->boolean('track_consistency')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('global_questions', function(Blueprint $table) {
            $table->dropColumn('track_consistency');
        });
    }
}
