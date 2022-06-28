<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('quizes', function(Blueprint $table){
            $table->uuid('id');
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('company_id')->nullable();
            $table->json('image_meta')->nullable();
            $table->json("form_json")->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('payload_id')->nullable();
            $table->unsignedInteger('privacy_id')->nullable();
            $table->boolean('replay')->default(false);
            $table->integer('state')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('expired_at')->nullable();

            $table->softDeletes();
            $table->primary("id");
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('updated_by')->references('id')->on('profiles');
            $table->foreign('payload_id')->references('id')->on('channel_payloads');
            $table->foreign('privacy_id')->references('id')->on('privacies');


            
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
        Schema::drop('quizes');
    }
}
