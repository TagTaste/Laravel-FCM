<?php

use Illuminate\Database\Seeder;

class ProductReviewBatchesColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('collaborate_batches_color')->insert([

            [
                'name' => '#2869B5'
            ],
            [
                'name' => '#F5A623'
            ],
            [
                'name' => '#F8E71C'
            ],
            [
                'name' => '#8B572A'
            ],
            [
                'name' => '#7ED321'
            ],
            [
                'name' => '#417505'
            ]

        ]);
    }
}
