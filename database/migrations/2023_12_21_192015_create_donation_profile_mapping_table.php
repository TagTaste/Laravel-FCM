<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonationProfileMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('donation_profile_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('donation_organisation_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("profile_id")->references("id")->on("profiles");
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
        Schema::dropIfExists('donation_profile_mapping');
    }
}
