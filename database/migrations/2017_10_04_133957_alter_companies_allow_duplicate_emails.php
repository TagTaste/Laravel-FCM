<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompaniesAllowDuplicateEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $keyExists = DB::select(
            DB::raw(
                'SHOW KEYS
                    FROM companies
                    WHERE Key_name=\'companies_email_unique\''
            )
        );
        if(!$keyExists){
            return true;
        }
        Schema::disableForeignKeyConstraints();
        Schema::table("companies",function(Blueprint $table){
           
            $table->dropIndex('companies_email_unique');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //This would fail although.
        Schema::table("companies",function(Blueprint $table){
            $table->unique('email');
        });
    }
}
