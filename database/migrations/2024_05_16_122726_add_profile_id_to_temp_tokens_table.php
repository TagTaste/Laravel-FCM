<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileIdToTempTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_tokens', function (Blueprint $table) {
            $table->integer('profile_id')->after('questionnaire_share_id')->unsigned()->nullable();
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_tokens',function(Blueprint $table){
            $table->dropForeign(['profile_id']);
            $table->dropColumn(['profile_id']);
        });
    }
}
