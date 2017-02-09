<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CompanyTypeTableSeeder extends Seeder {

    public function run()
    {
        $types = "privately held, government agency, non profit, partnership firm, one person company, educational";
        $types = explode(",",ucwords($types));
        $models = [];
        foreach($types as $type){
            $models[]['name'] = $type;
        }
        \App\Company\Type::insert($models);
    }

}