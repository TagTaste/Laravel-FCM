<?php

use Illuminate\Database\Seeder;

class CollaborateTastingMethodologyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Monadic Sequence (Blind)','description'=>'All products will be provided sequentially to the tasters without revealing their brand & related information.'];
        $data[] = ['name'=>'Monadic Sequence (Apparent)','description'=>'All products will be provided sequentially to the tasters along with their brand & related information.'];

        \DB::table('collaborate_tasting_methodology')->insert($data);
    }
}
