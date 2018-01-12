<?php

namespace App;

use App\Notifications\PasswordCreate;
use App\Notifications\PasswordReset;
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
        'name', 'email', 'password', 'is_active', 'social_registration','email_token','verified_at','invite_code','used_invite_code'
    ];


    protected $visible = ['name','email','profile','id','verified_at','invite_code','used_invite_code'];

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
    
        self::updated(function($user){
            \App\Documents\Profile::create($user->profile);
        });
    }

    public function restore()
    {
        return $this->softRestore();
    }

    public function sendPasswordResetNotification($token)
    {
        // Check if user's password exists
        $isUserPassExists = \DB::table('users')->whereNull('password')->where('email',$this->email)->exists();
        if($isUserPassExists) {
            $this->notify(new PasswordCreate($token));
        } else {
            $this->notify(new PasswordReset($token));
        }

    }
}
