<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuestionnaireHeaderHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('questionnaire_header_helpers', function(Blueprint $table){            
            $table->text('title')->nullable()->change();
            $table->unique('header_id')->change();            
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
        Schema::table('questionnaire_header_helpers', function(Blueprint $table){
           

        });
    }
}
