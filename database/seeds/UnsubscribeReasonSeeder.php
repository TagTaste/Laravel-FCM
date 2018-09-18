<?php

use Illuminate\Database\Seeder;

class UnsubscribeReasonSeeder extends Seeder
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
        $data[] = ['reason'=>'Your emails are not relevant to me.'];
        $data[] = ['reason'=>'Your emails are too frequent'];
        $data[] = ['reason'=>'I no longer want to recieve these emails'];
        

        \DB::table('unsubscribe_reasons')->insert($data);
    }
}
