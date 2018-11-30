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
        $data[] = ['id'=>1, 'text'=>'when owner create a group'];
        $data[] = ['id'=>2, 'text'=>'when admin added someone'];
        $data[] = ['id'=>3, 'text'=>'when admin remove someone'];
        $data[] = ['id'=>4, 'text'=>'when someone is left'];
        $data[] = ['id'=>5, 'text'=>'group name change'];
        $data[] = ['id'=>6, 'text'=>'image change'];
        $data[] = ['id'=>7, 'text'=>'make admin'];
        $data[] = ['id'=>8, 'text'=>'remove admin'];

        \DB::table('chat_message_type')->insert($data);
    }
}
