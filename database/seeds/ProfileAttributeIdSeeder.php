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

        $types = ProfileType::select('id','type')->get();
        $profileAttributes = [];
        foreach($types as $type){
            $name = strtolower($type->type) . '_id';
            $profileAttributes[] =  ['name'=>$name,'label'=>$name,'enabled'=>0,'required'=>0,'user_id'=> $userId,'profile_type_id'=>$type->id];
        }

        ProfileAttribute::insert($profileAttributes);
    }
}
