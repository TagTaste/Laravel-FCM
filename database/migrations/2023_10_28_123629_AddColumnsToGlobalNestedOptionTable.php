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
            $table->double('pos', 32, 16)->after('value')->default(0);
            $table->integer('aroma_list_id')->after('is_active')->nullable();
            $table->softDeletes()->after('updated_at');
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
            $table->dropColumn('updated_at');
        });
    }
}
