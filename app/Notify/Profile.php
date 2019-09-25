<?php

namespace App\Notify;

use App\Profile as BaseProfile;
use Illuminate\Notifications\Notifiable;

class Profile extends BaseProfile
{
    use Notifiable;
    protected $fillable = [];

    protected $with = [];

    protected $visible = ['id','user_id','name','designation','image_meta','imageUrl','tagline','about','handle'];

    protected $appends = ['name','designation','imageUrl'];
    
    public function getDesignationAttribute()
    {
       return $this->professional !== null ? $this->professional->designation : null;
    }

}
