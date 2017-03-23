<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class Company_statusTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $status = "Operating, operating subsidiary, reorganizing, out of business, acquired";
        $status = explode(',',ucwords($status));
        
        $models= [];
        
        foreach ($status as $status) {
        	$status = trim($status);
        	$models[] = ['name' => $status , 'description' =>$status];
        	
        }
        
        \App\Company\Status::insert($models);


    }

}