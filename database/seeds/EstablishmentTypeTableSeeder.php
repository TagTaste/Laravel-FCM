<?php

use Illuminate\Database\Seeder;
use Laracasts\TestDummy\Factory as TestDummy;

// composer require laracasts/testdummy

class EstablishmentTypeTableSeeder extends Seeder {

    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Casual Dining'];
        $data[] = ['name'=>'QSR'];
        $data[] = ['name'=>'Fine Dining'];
        $data[] = ['name'=>'Cafe'];
        $data[] = ['name'=>'Pubs & Bars'];
        $data[] = ['name'=>'Lounges & Clubs'];
        $data[] = ['name'=>'Bakeries'];
        $data[] = ['name'=>'Delivery Kitchens'];
        $data[] = ['name'=>'Beverage Shops'];
        $data[] = ['name'=>'Dhabas'];
        $data[] = ['name'=>'Food courts'];
        $data[] = ['name'=>'Dessert Parlours'];
        $data[] = ['name'=>'Meat Shops'];
        \DB::table('establishment_types')->insert($data);
    }

}