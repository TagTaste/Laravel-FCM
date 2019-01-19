<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("module_versions",function(Blueprint $table){
            $table->increments('id');
            $table->string("module_name")->nullable();
            $table->string('compatible_version');
            $table->string('latest_version')->nullable();
            $table->string('platform');
            $table->text("title")->nullable();
            $table->text("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_versions');
    }
}
