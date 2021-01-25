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
            $table->unsignedInteger('company_id');
            $table->string('title');
            $table->text('description');
            $table->string('media_meta');
            $table->string('image_meta');
            $table->json("form_json");
            $table->unsignedInteger('profile_updated_by');
            $table->text('invited_profile_ids');
            $table->date('expired_at')->nullable();
            $table->enum('state',["1","2"])->default("1");
            $table->boolean('is_active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')  ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->timestamp('published_at')->nullable();

            $table->primary("id");
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
