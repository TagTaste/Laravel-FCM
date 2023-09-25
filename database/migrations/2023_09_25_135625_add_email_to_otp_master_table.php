<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailToOtpMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('otp_master', function (Blueprint $table){
            $table->string('mobile')->nullable()->change();
            $table->string('email')->nullable()->after('mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('otp_master', function (Blueprint $table){
            $table->string('mobile')->nullable(false)->change();
            $table->dropColumn('email');
        });
    }
}
