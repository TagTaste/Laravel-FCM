<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLandingQuickLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('landing_quick_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',50);
            $table->string('image');	
            $table->string('model_name',50);	
            $table->decimal('sort_order',4,2);
            $table->boolean('is_active')->default(0);
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
        Schema::drop('landing_quick_links');
    }
}
