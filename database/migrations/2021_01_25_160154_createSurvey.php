<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function(Blueprint $table){
            $table->char('id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('company_id');
            $table->string('title');
            $table->text('description');
            $table->string('media_meta');
            $table->string('image_meta');
            $table->longText("form_json");
            $table->unsignedInteger('profile_updated_by');
            $table->text('invited_profile_ids');
            $table->timestamp('expiry_date');
            $table->enum('state',["1","2"])->default("1");
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('published_at')->nullable();


            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('company_id')->references('id')->on('companies');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('surveys');
    }
}
