<?php

use Illuminate\Database\Seeder;

class CompanyTypeAddTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //4 for update name of type company

        \App\Company\Type::where('id',4)->update(['name'=>'Partnership']);
        $types = "Public Company, Self Employed, Self Owned";
        $types = explode(",",ucwords($types));
        $models = [];
        foreach($types as $type){
            $models[]['name'] = $type;
        }
        \App\Company\Type::insert($models);
    }
}
