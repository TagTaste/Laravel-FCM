<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDonationToCollaborateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('collaborate_applicants', function (Blueprint $table){
            $table->boolean('is_donation')->default(false);
            $table->unsignedInteger('donation_organisation_id')->nullable();

            $table->foreign("donation_organisation_id")->references("id")->on("donation_organisations");
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
        Schema::table('collaborate_applicants', function(Blueprint $table){
            $table->dropColumn(['is_donation','donation_organisation_id']);
        });
    }
}
