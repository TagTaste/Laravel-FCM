<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('temp_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_share_id')->unsigned();
            $table->string('email');
            $table->string('source');
            $table->text('token');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('temp_tokens');

    }
}
