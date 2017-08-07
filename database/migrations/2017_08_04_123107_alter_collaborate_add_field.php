<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaborateAddField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("collaborates",function(Blueprint $table){
            //drop previous column
            $table->dropColumn("i_am");
            $table->dropColumn("purpose");
            $table->dropColumn("deliverables");
            $table->dropColumn("who_can_help");

            //add new column
            $table->text('looking_for')->change();
            $table->text('description');
            $table->string("location")->change();
            $table->text('project_commences')->nullable();
            $table->string("image1")->nullable();
            $table->string("image2")->nullable();
            $table->string("image3")->nullable();
            $table->string("image4")->nullable();
            $table->string("image5")->nullable();

            $table->string('duration')->nullable();
            $table->string('financials')->nullable();
            $table->string('eligibility_criteria')->nullable();
            $table->string('occassion')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("collaborates",function(Blueprint $table){
            $table->string('i_am');
            $table->text('purpose');
            $table->text('deliverables');
            $table->text('who_can_help');

            $table->string("location")->nullable()->change();
            $table->text("looking_for")->nullable()->change();

            $table->dropColumn("description");
            $table->dropColumn("project_commences");
            $table->dropColumn("image1");
            $table->dropColumn("image2");
            $table->dropColumn("image3");
            $table->dropColumn("image4");
            $table->dropColumn("image5");

            $table->dropColumn("duration");
            $table->dropColumn("financials");
            $table->dropColumn("eligibility_criteria");
            $table->dropColumn("occassion");
        });
    }
}
