<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class OtpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_master', function (Blueprint $table) {
            $table->increments('id');
            $table->string("otp");
            $table->string("country_code");
            $table->string("mobile");
            $table->unsignedInteger("profile_id");
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->string("service")->nullable();
            $table->string("source")->nullable();
            $table->string("platform")->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->index("created_at");
            $table->index("otp");
            $table->index("profile_id");
            $table->index("mobile");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('otp_master');
    }
}
