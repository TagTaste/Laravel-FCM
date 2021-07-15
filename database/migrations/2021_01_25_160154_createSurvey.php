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
            $table->char('id',36);
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('company_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->json('video_meta')->nullable();
            $table->json('image_meta')->nullable();
            $table->json("form_json")->nullable();
            $table->unsignedInteger('profile_updated_by')->nullable();
            $table->json('invited_profile_ids')->nullable();
            $table->date('expired_at')->nullable();
            $table->enum('state',["1","2"])->default("1");
            $table->boolean('is_active')->default("1");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')  ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->timestamp('published_at')->nullable();

            $table->primary("id");
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('profile_updated_by')->references('id')->on('profiles');
            
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
