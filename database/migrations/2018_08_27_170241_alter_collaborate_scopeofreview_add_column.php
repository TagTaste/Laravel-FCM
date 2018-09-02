<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateScopeofreviewAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->string("brand_name")->nullable();
            $table->string("brand_logo")->nullable();
            $table->integer('methodology_id')->unsigned()->nullable();
            $table->foreign('methodology_id')->references('id')->on('collaborate_tasting_methodology');
            $table->json("age_group")->nullable();
            $table->json("gender_ratio")->nullable();
            $table->integer("no_of_expert")->nullable();
            $table->integer("no_of_veterans")->nullable();
            $table->boolean('is_product_endorsement')->default(0);
            $table->integer("no_of_batches")->default(4);
            $table->integer("global_question_id")->nullable();
            $table->text("taster_instruction")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborates',function(Blueprint $table){
            $table->dropForeign(['methodology_id']);
            $table->dropColumn(['methodology_id','age_group','gender_ratio','no_of_expert','no_of_veterans'
                ,'is_product_endorsement','brand_name','brand_logo','no_of_batches','taster_instruction','global_question_id']);

        });
    }
}
