<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterModelFlagReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('model_flag_reasons', function (Blueprint $table){
            $table->unsignedInteger('flag_reason_id')->nullable()->change();
            $table->string('reason', 2000)->after('flag_reason_id');
            $table->string('slug')->after('reason');
            $table->unsignedInteger('profile_id')->after('slug')->nullable();
            $table->unsignedInteger('company_id')->after('profile_id')->nullable();
            $table->timestamp('deleted_at')->after('updated_at')->nullable();

            // Define foreign key constraints
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('model_flag_reasons', function (Blueprint $table) {
            // Drop the newly added columns
            $table->dropColumn('reason');
            $table->dropColumn('slug');
            $table->dropColumn('profile_id');
            $table->dropColumn('company_id');
            $table->dropColumn('deleted_at');
            
            // Revert the changes made to the existing column
            $table->unsignedInteger('flag_reason_id')->nullable(false)->change();
        });
    }
}
