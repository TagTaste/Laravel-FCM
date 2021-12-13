<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensoryWorkshopRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sensory_workshop_request', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("profile_id");
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->boolean("workshop_request")->default(0);
            $table->boolean("expert_request")->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::drop('sensory_workshop_request');
    }
}
