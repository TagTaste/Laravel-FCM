<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCollation extends Migration
{
    private $tables = [
        'chat_messages','chats',
        'collaborates','collaboration_fields',
        'comments','feedback','invites','jobs','notifications','photos','albums',
        'recipes','shoutouts','profiles','users'
    ];
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach($this->tables as $tablename) {
            Schema::table($tablename, function () use ($tablename) {
                \DB::statement("ALTER TABLE {$tablename} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                \DB::statement("REPAIR TABLE {$tablename}");
                \DB::statement("OPTIMIZE TABLE {$tablename}");
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
        foreach($this->tables as $tablename){
            Schema::table($tablename, function () use ($tablename) {
                \DB::statement("ALTER TABLE {$tablename} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
                \DB::statement("REPAIR TABLE {$tablename}");
                \DB::statement("OPTIMIZE TABLE {$tablename}");
            });
        }
    }
}
