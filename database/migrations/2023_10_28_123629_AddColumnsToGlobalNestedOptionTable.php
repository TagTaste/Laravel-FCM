<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToGlobalNestedOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_nested_option', function (Blueprint $table){
            $table->double('pos', 15, 8)->after('value')->default(0);
            $table->integer('aroma_list_id')->after('is_active')->nullable();
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
        Schema::table('global_nested_option', function (Blueprint $table){
            $table->dropColumn('pos');
            $table->dropColumn('aroma_list_id');
        });
    }
}
