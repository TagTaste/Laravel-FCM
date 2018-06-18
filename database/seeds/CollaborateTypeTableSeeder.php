<?php

use Illuminate\Database\Seeder;

class CollaborateTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('collaborate_types')->insert([

            [
                'name' => 'Vegetarian'
            ],
            [
                'name' => 'Contain Egg'
            ],
            [
                'name' => 'Non - Vegetarian'
            ]

        ]);
    }
}
