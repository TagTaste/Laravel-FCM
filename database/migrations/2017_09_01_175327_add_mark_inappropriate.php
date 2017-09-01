<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarkInappropriate extends Migration
{
    private $tables = ['photos','recipes','shoutouts','jobs','collaborates','profiles','companies',
                        'photo_shares','recipe_shares','shoutout_shares','job_shares'
                        ,'collaborate_shares'
    ];
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach($this->tables as $table){
            Schema::table($table,function(Blueprint $schema){
                $schema->timestamp('inappropriate')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach($this->tables as $table){
            Schema::table($table,function(Blueprint $schema){
                $schema->dropColumn('inappropriate');
            });
        }
    }
}
