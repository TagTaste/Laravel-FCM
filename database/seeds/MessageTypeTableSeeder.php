<?php

use Illuminate\Database\Seeder;

class MessageTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [];
        $data[] = ['id'=>1, 'text'=>'added'];
        $data[] = ['id'=>2, 'text'=>'removed'];
        $data[] = ['id'=>3, 'text'=>'admin'];
        $data[] = ['id'=>4, 'text'=>'changed group icon'];
        $data[] = ['id'=>5, 'text'=>'changed group name'];

        \DB::table('chat_message_type')->insert($data);
    }
}
