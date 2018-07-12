<?php

use Illuminate\Database\Seeder;

class CollaborateReviewHeader extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('collaborate_tasting_header')->insert([
            ['header_type' => "Instruction",
            'is_active' => 1,
            'collaborate_id' => 436 ],
            ['header_type' => "Apperance",
                'is_active' => 1,
                'collaborate_id' => 436 ],
            ['header_type' => "Aroma",
                'is_active' => 1,
                'collaborate_id' => 436 ],
            ['header_type' => "Aromatic",
                'is_active' => 1,
                'collaborate_id' => 436 ],
            ['header_type' => "Taste",
                'is_active' => 1,
                'collaborate_id' => 436 ],
            ['header_type' => "Oral Texture",
                'is_active' => 1,
                'collaborate_id' => 436 ],
            ['header_type' => "Overall preferance",
                'is_active' => 1,
                'collaborate_id' => 436 ],
        ]);
    }
}
