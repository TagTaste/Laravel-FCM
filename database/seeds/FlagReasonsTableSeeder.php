<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FlagReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'reason' => 'Review duration is longer or shorter than the specific time',
                'slug' => 'review_duration',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'reason' => 'Review start time is not in the specific time span',
                'slug' => 'review_start_time',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        \DB::table('flag_reasons')->insert($data);
    }
}
