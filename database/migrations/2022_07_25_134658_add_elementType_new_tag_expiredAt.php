<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddElementTypeNewTagExpiredAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('survey_question_type', function(Blueprint $table){
            $table->string('element_type')->nullable();        
            $table->timestamp('new_tag_expired_at')->nullable();            
    
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
        Schema::table('survey_question_type', function(Blueprint $table){
            $table->dropColumn('new_tag_expired_at');
            $table->dropColumn('element_type');

        });
    }
}
