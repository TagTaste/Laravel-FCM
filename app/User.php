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

    public function getProfileAttributeId($name)
    {
        $attribute = \App\ProfileAttribute::select('id')->where('name','like', $name . '_id')->first();
        if(!$attribute){
            throw new \Exception("Could not find $name attribute");
        }
        return $attribute;
    }

    public function getProfileId($name)
    {
        $attribute = $this->getProfileAttributeId($name);

        $profile = \App\Profile::select('id')->where('profile_attribute_id','=',$attribute->id)->where('user_id','=',$this->id)->first();
        if(!$profile){
            throw new \Exception("User has not created $name profile yet.");
        }

        return $profile->id;
    }

    public function getFoodieProfileId()
    {
        try {
            $profileId = $this->getProfileId('foodie');
        } catch (\Exception $e){
            throw $e;
        }

        return $profileId;
    }

    public function getChefProfileId() {

        try {
            $profileId = $this->getProfileId("chef");
        } catch (\Exception $e){
            //cascade, get foodie profile in the end.
            $profileId = $this->getFoodieProfileId();
        }

        return $profileId;

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

    public function getArticles()
    {
        $profileAttributeIds = \App\ProfileAttribute::where('name','like','%_id')->get()->pluck('id');

        $userId = $this->id;

        return \App\Article::with('template','dish')
            ->join('profiles','profiles.id','=','articles.author_id')
            ->where('profiles.user_id','=',$userId)
            ->whereIn('profiles.profile_attribute_id',$profileAttributeIds)
            ->get();
    }

    public function social()
    {
        return $this->hasMany('\App\SocialAccount');
    }
}
