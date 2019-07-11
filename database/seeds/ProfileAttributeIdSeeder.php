<?php

use App\ProfileAttribute;
use App\ProfileType;
use App\Profile\User;
use Illuminate\Database\Seeder;

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

        //image, enabled, file input type
        foreach($types as $type){
            $name = strtolower($type->type) . "_image";
            $profileAttributes[] =  ['name'=>$name,'label'=>ucwords(str_replace("_", " ",$name)),'enabled'=>1,'required'=>0,'user_id'=> $userId,'profile_type_id'=>$type->id,'input_type'=>'file'];
        }

        ProfileAttribute::insert($profileAttributes);
    }
}
