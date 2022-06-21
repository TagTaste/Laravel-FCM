<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizesSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('quizes_shares', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->uuid('quiz_id');
            $table->unsignedInteger('payload_id')->nullable();
            $table->text('content')->nullable();
            $table->foreign('profile_id')->references("id")->on("profiles")->onDelete('cascade');
            $table->foreign('quiz_id')->references("id")->on("quizes");
            $table->foreign('payload_id')->references("id")->on("channel_payloads")->onDelete('cascade');
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
        Schema::dropIfExists('quizes_shares');

    }
}
