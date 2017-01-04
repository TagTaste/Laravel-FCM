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
        $supplier = ProfileType::select('id','type')->where('type','like','supplier')->first();
        $default = ProfileType::select('id','type')->where('type','like','default')->first();
        $outlet = ProfileType::select('id','type')->where('type','like','outlet')->first();
        $profileAttributes = [];
        $admin = User::getAdmin();

        //attributes for foodie
        if($foodie){
            $attributes = ['name'=>'text','location'=>'text','About Yourself'=>'textarea','phone'=>'text','email'=>'text','Fun Facts'=>'textarea',"Ingredients that you cannot live without"=>'textarea'];

            foreach($attributes as $name => $inputType){
                $profileAttributes[] = [
                    'name'=>str_replace(" ","_",strtolower($name)),'label'=>ucwords($name),
                    'enabled'=>1,'required'=>1,'user_id'=> $admin->id,
                    'profile_type_id'=>$foodie->id,
                    'input_type'=>$inputType];
            }

        }

        if($chef){
            $attributes = ['work experience'=>'textarea','chef awards'=>'checkbox','recognition'=>'textarea','certifications'=>'text', 'establishment types'=>'checkbox'];

            foreach($attributes as $name => $inputType){
                $profileAttributes[] = ['name'=>str_replace(" ","_",strtolower($name)),'label'=>ucwords($name),'enabled'=>1,'required'=>0,'user_id'=> $admin->id,'profile_type_id'=>$chef->id,'input_type'=>$inputType];
            }
        }

        if($supplier) {
            $attributes = ['name'=>'text','phone'=>'text','email'=>'text','About Yourself'=>'textarea','Established on'=>'text','Major milestone'=>'text',"Number of customers"=>'text','clients'=>'text','Annual revenue'=>'text', 'Speciality'=>'text','Pincodes Catered'=>'text','Past Projects'=>'text','address'=>'text'];

            foreach($attributes as $name => $inputType) {
                $profileAttributes[] = [
                    'name'=>str_replace(" ","_",strtolower($name)),'label'=>ucwords($name),
                    'enabled'=>1,'required'=>1,'user_id'=> $admin->id,
                    'profile_type_id'=>$supplier->id,
                    'input_type'=>$inputType
                ];
            }
        }

        if($default){
            $attributes = ['cuisine'=>'checkbox'];

            foreach($attributes as $name => $inputType){
                $profileAttributes[] = ['name'=>str_replace(" ","_",strtolower($name)),'label'=>ucwords($name),'enabled'=>1,'required'=>0,'user_id'=> $admin->id,'profile_type_id'=>$default->id,'input_type'=>$inputType];
            }
        }

        if($outlet) {
            $attributes = ['name'=>'text','phone'=>'text','email'=>'text','address'=>'text','About Yourself'=>'textarea','cuisine'=>'checkbox','establishment types'=>'checkbox',"signature dishes"=>'text','famous for'=>'text','awards'=>'text'];

            foreach($attributes as $name => $inputType) {
                $profileAttributes[] = [
                    'name'=>str_replace(" ","_",strtolower($name)),'label'=>ucwords($name),
                    'enabled'=>1,'required'=>1,'user_id'=> $admin->id,
                    'profile_type_id'=>$outlet->id,
                    'input_type'=>$inputType
                ];
            }
        }

        ProfileAttribute::insert($profileAttributes);
    }

}