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
                'name' => 'Black'
            ],
            [
                'name' => 'Grey'
            ],
            [
                'name' => 'Yellow'
            ],
            [
                'name' => 'Red'
            ],
            [
                'name' => 'Blue'
            ],
            [
                'name' => 'Green'
            ]

        ]);
    }
}
