<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FlagReasonConditionsTableSeeder extends Seeder
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
                'flag_reason_id' => 1,
                'condition_value' => '180',
                'condition_slug' => 'min_duration',
                'condition_description' => 'minimun value of duration in seconds',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'flag_reason_id' => 1,
                'condition_value' => '1800',
                'condition_slug' => 'max_duration',
                'condition_description' => 'maximum value of duration in seconds',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'flag_reason_id' => 2,
                'condition_value' => '10:00:00',
                'condition_slug' => 'min_start_time',
                'condition_description' => 'Minimum time value to start an review',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'flag_reason_id' => 2,
                'condition_value' => '20:00:00',
                'condition_slug' => 'max_start_time',
                'condition_description' => 'Maximum time value to start an review',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        \DB::table('flag_reason_conditions')->insert($data);
    }
}
