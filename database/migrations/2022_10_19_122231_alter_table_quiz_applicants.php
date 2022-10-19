<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableQuizApplicants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_applicants', function (Blueprint $table) {
            $table->json('address')->nullable()->after('age_group');
            $table->string('hometown')->nullable();
            $table->string('city')->nullable();
            $table->string('current_city')->nullable();
            $table->string('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_applicants', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('hometown');
            $table->dropColumn('current_city');
            $table->dropColumn('city');
            $table->dropColumn('gender');
        });
    }
}
