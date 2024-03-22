<?php


namespace App\Traits;

use App\Profile;


trait CheckTTEmployee
{
    public function checkTTEmployee($profile_id){
        $email = Profile::where('id', $profile_id)->first()->user->email;
        if(!empty($email)){
            $email = explode('@', $email, 2);
        }

        if($email[1] === 'tagtaste.com'){
            return true;
        }
        return false;
    }
}