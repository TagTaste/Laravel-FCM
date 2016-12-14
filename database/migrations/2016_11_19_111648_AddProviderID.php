<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProviderID extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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

        Schema::table('users', function ($table) {
            $table->string('social_provider')->nullable()->after('remember_token');
            $table->string('social_provider_id')->nullable()->after('social_provider');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('social_provider');
            $table->dropColumn('social_provider_id');
        });
    }
}
