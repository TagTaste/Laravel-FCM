<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Profile;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'social_provider', 'social_provider_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile() {
        return $this->hasMany('\App\Profile');
    }

    public function getChefProfileId() {
        $attribute = \App\ProfileAttribute::select('id')->where('name','like','chef_id')->first();

        if($attribute){
            $profile = \App\Profile::select('id')->where('profile_attribute_id','=',$attribute->id)->first();

            if($profile){
                return $profile->id;
            }
        }
    }

    public static function getAdmin()
    {
        return static::select('id','name')->whereHas("roles",function($query){
            $query->where('name','like','admin');
        })->first();
    }

    public function attachDefaultRole()
    {
        $role = Role::where('name', '=', 'foodie')->first();

        if(!$role){
            throw new \Exception("Could not find default role");
        }


        $this->attachRole($role);
        return;
    }

    public function createDefaultProfile()
    {
        Profile::createDefaultProfile($this->id);
        return;
    }
}
