<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_group', function(Blueprint $table){
            $table->increments('id');
            $table->string('title', 256);
            $table->text('description')->nulllable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['id']);
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
        Schema::dropIfExists('user_group');
    }
}
