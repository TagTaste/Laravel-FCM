<?php

use Illuminate\Database\Seeder;
use App\ProfileAttribute;
use App\Role;
use App\User;

class ProfileAttributeIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::whereHas("roles",function($query){
            $query->where('name','like','admin');
        })->first();

        if($admin){
            ProfileAttribute::insert(
                [
                    ['name'=>'chef_id','label'=>'chef_id','enabled'=>0,'required'=>0,'user_id'=> $admin->id,'']
                ]);
        }

    }
}
