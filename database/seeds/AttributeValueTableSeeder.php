<?php

use Illuminate\Database\Seeder;

use App\AttributeValue;
use App\ProfileAttribute;

class AttributeValueTableSeeder extends Seeder {

    public function run()
    {
        $cuisine = ProfileAttribute::where('name','like','cuisine')->first();

        if($cuisine){
            $cuisines = [];
            $cuisines[] = ['name'=>'Chinese','value'=>'chinese'];
            $cuisines[] = ['name'=>'Italian','value'=>'Italian'];
            $cuisines[] = ['name'=>'Indian','value'=>'Indian'];

            foreach($cuisines as &$value){
                $value['attribute_id'] = $cuisine->id;
                $value['default'] = 0;
            }
        }

        $chefAward = ProfileAttribute::where("name",'like','chef_awards')->first();

        if($chefAward){

            $awards[] = ['name'=>'JBF Award','value'=>'jbf'];
            $awards[] = ['name'=>'The Chefs\' Choice Award','value'=>'chefchoice'];

            foreach($awards as &$value){
                $value['attribute_id'] = $chefAward->id;
                $value['default'] = 0;
            }
        }

        $values = array_merge($cuisines,$awards);

        if(count($values)){
            AttributeValue::insert($values);
        }
    }

}