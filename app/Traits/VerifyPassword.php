<?php


namespace App\Traits;

trait VerifyPassword
{
    public function verifyPassword($password){
        if(empty($password)){
            return ["error" => "Password is required"];
        } else if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{6,}$/', $password)){
            return ["error" => "Please enter a valid password that meets the criteria: minimum length of 6 characters, at least one uppercase letter, one lowercase letter, one numeric character, and one special character"];
        }
        
    }
}