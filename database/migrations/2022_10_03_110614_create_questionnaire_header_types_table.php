<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireHeaderTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('questionnaire_header_types', function (Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->integer('header_selection_type');
            $table->float('sort_order');
            $table->boolean('is_active')->default(false);
            $table->string('header_slug');

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
        Schema::drop('questionnaire_header_types');

    }
}
