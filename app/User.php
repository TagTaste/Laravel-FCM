<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait, SoftDeletes {
        SoftDeletes::restore as softRestore;
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'social_registration'
    ];


    protected $visible = ['name','email','profile','id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];
    
    public function profile() {
        return $this->hasOne('\App\Recipe\Profile');
    }
    
    public function completeProfile()
    {
        return $this->hasOne(\App\Profile::class);
    }
    
    public static function boot()
    {
        parent::boot();

        self::created(function(User $user){
            $user->profile()->create([]);
        });
    }

    public function restore()
    {
        return $this->softRestore();
    }
}