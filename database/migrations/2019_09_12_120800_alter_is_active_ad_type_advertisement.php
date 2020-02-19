<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIsActiveAdTypeAdvertisement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->renameColumn('is_expired', 'is_active');
            $table->string('type', 255)->nullable()->default(null);
            $table->integer('type_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->renameColumn('is_active', 'is_expired');
            $table->dropColumn('type');
            $table->dropColumn('type_id');
        });
    }
}
