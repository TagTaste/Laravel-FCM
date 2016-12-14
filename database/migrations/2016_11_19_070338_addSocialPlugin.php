<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialPlugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('facebook_id')->nullable()->after('remember_token');
            $table->string('google_id')->nullable()->after('facebook_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('users','facebook_id')){
            Schema::table('users', function ($table) {
                $table->dropColumn('facebook_id');
            });
        }

        if(Schema::hasColumn('users','google_id')){
            Schema::table('users', function ($table) {
                $table->dropColumn('google_id');
            });
        }
    }
}
