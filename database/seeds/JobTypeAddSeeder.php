<?php

use Illuminate\Database\Seeder;
// add new job type
class JobTypeAddSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Job\Type::insert(
            [
                ['name' => 'Internship']
            ]
        );
    }
}
