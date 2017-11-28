<?php

use Illuminate\Database\Seeder;

class companyNewTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Company\Type::where('id',1)->update(['name'=>'Privately Held Company']);
        \App\Company\Type::where('id',2)->update(['name'=>'Public Company']);
        \App\Company\Type::where('id',3)->update(['name'=>'Partnership Firm']);
        \App\Company\Type::where('id',4)->update(['name'=>'Sole Proprietorship']);
        \App\Company\Type::where('id',5)->update(['name'=>'One Person Company']);
        \App\Company\Type::where('id',6)->update(['name'=>'Self Employed']);

        $types = ['Self Owned','Non Profit Organisation','Non Governmental Organisation','Government Agency',
            'Diplomatic Embassy','Educational Institution','Others'];
        $models = [];
        foreach($types as $type){
            $models[]['name'] = $type;
        }
        \App\Company\Type::insert($models);

    }
}
