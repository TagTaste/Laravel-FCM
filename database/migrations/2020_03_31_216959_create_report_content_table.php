<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('report_type_id')->unsigned();
            $table->string('report_type_name', 255)->nullable()->default(null);
            $table->text('report_comment')->nullable()->default(null);
            $table->integer('payload_id')->unsigned()->nullable()->default(null);
            $table->string('data_type', 50)->nullable()->default(null);
            $table->string('data_id', 100)->nullable()->default(null);
            $table->boolean('is_shared')->default(false);
            $table->integer('shared_id')->unsigned()->nullable()->default(null);
            $table->integer('reported_profile_id')->unsigned()->nullable()->default(null);
            $table->integer('reported_company_id')->unsigned()->nullable()->default(null);
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
        Schema::dropIfExists('report_content');
    }
}