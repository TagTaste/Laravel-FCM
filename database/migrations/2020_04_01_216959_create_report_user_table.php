<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('report_type_id')->unsigned();
            $table->string('report_type_name', 255)->nullable()->default(null);
            $table->text('report_comment')->nullable()->default(null);
            $table->string('user_type', 50)->nullable()->default(null);
            $table->integer('user_id')->unsigned();
            $table->integer('profile_id')->unsigned();
            $table->foreign('profile_id')->references("id")->on("profiles");
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_user');
    }
}