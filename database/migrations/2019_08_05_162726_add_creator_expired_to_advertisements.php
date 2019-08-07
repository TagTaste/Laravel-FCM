<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatorExpiredToAdvertisements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("advertisements",function(Blueprint $table){
            $table->softDeletes();
            $table->dateTime('expired_at')->nullable();
            $table->boolean('is_expired')->default(0);
            $table->integer('profile_id')->unsigned()->nullable();
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->text('link');
            $table->text('image');
            $table->text('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("advertisements",function(Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('expired_at');
            $table->dropColumn('is_expired');
            $table->dropForeign('profile_id');
            $table->dropColumn('profile_id');
            $table->dropColumn('link');
            $table->dropColumn('image');
            $table->dropColumn('cities');
        });
    }
}
