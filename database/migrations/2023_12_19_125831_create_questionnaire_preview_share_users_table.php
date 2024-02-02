<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnairePreviewShareUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_preview_share_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->integer('questionnaire_id');
            $table->string('otp');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('expired_at')->nullable();
            $table->integer('attempts')->default(0);
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
        Schema::dropIfExists('questionnaire_preview_share_users');

    }
}
