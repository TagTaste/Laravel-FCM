<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('collaborate_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('collaborate_id')->unsigned();
            $table->foreign('collaborate_id')->references('id')->on('collaborates');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->boolean('mail_sent')->default(0);
            $table->boolean('notification_sent')->default(0); 
            $table->integer('uploaded_by')->nullable();            
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
        //
        Schema::drop('collaborate_reports');
    }
}
