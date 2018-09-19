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
        $data[] = ['reason'=>'Your emails are not relevant to me'];
        $data[] = ['reason'=>'Your emails are too frequent'];
        $data[] = ['reason'=>'I don\'t\remember signing up for this'];
        $data[] = ['reason'=>'I no longer want to recieve these emails'];
        $data[] = ['reason'=>'The emails are spam and should be reported']; 
        

        \DB::table('unsubscribe_reasons')->insert($data);
    }
}
