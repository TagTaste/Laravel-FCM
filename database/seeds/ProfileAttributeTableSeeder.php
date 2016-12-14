<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use App\ProfileAttribute;
use App\ProfileType;
use App\User;

class ProfileAttributeTableSeeder extends Seeder {

    public function run()
    {
        $chef = ProfileType::select('id','type')->where('type','like','chef')->first();
        $foodie = ProfileType::select('id','type')->where('type','like','foodie')->first();
        $profileAttributes = [];
        $admin = User::getAdmin();

        //attributes for foodie
        if($foodie){
            $attributes = ['name'=>'text','location'=>'text','About Yourself'=>'textarea','phone'=>'text','email'=>'text',
                'Facebook'=>'text','LinkedIn'=>'text','Instagram'=>'text','Pinterst'=>'text','Youtube'=>'text','Fun Facts'=>'textarea',"Ingredients that you cannot live without"=>'textarea'];

            foreach($attributes as $name => $inputType){
                $profileAttributes[] = ['name'=>str_replace(" ","_",strtolower($name)),'label'=>ucwords($name),'enabled'=>1,'required'=>1,'user_id'=> $admin->id,'profile_type_id'=>$foodie->id,'input_type'=>$inputType];
            }

        }

        if($chef){
            $attributes = ['work experience'=>'textarea','cuisine'=>'checkbox','awards'=>'checkbox','recognition'=>'textarea','certifications'=>'text'];

            foreach($attributes as $name => $inputType){
                $profileAttributes[] = ['name'=>str_replace(" ","_",strtolower($name)),'label'=>ucwords($name),'enabled'=>1,'required'=>1,'user_id'=> $admin->id,'profile_type_id'=>$chef->id,'input_type'=>$inputType];
            }
        }

        ProfileAttribute::insert($profileAttributes);
    }

}