<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeStampToAppInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('app_info', function (Blueprint $table) {
            //
            $table->timestamp('created_at')->default(null);
            $table->timestamp('updated_at')->default(null);    
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
        Schema::table('app_info', function (Blueprint $table) {
            //
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

    }
}
