<?php

use Illuminate\Database\Seeder;
use App\ProfileAttribute;
use App\User;
use App\ProfileType;

class ProfileAttributeIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $userId = null;
        $admin = User::getAdmin();

        if($admin){
            $userId = $admin->id;
        }

        $types = ProfileType::where('type','!=','Default')->select('id','type')->get();
        $profileAttributes = [];

        // chef_id, foodie_id, etc
        foreach($types as $type){
            $name = strtolower($type->type) . "_id";
            $profileAttributes[] =  ['name'=>$name,'label'=>$name,'enabled'=>0,'required'=>0,'user_id'=> $userId,'profile_type_id'=>$type->id,'input_type'=>null];
        }

        //image, enabled, file input type
        foreach($types as $type){
            $name = strtolower($type->type) . "_image";
            $profileAttributes[] =  ['name'=>$name,'label'=>ucwords(str_replace("_", " ",$name)),'enabled'=>1,'required'=>0,'user_id'=> $userId,'profile_type_id'=>$type->id,'input_type'=>'file'];
        }

        //social

        $social = ['Facebook'=>'text','LinkedIn'=>'text','Instagram'=>'text','Pinterst'=>'text','Youtube'=>'text'];

        foreach($social as $platform => $inputType){
            foreach($types as $type){
                $name = $type->type . " " . $platform;
                $profileAttributes[] = ['name'=>str_replace(" ","_",strtolower($name)),'label'=>ucwords($name),'enabled'=>1,'required'=>0,'user_id'=> $admin->id,'profile_type_id'=>$type->id,'input_type'=>$inputType];
            }
        }

        ProfileAttribute::insert($profileAttributes);
    }
}
