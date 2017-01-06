<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAccount extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','provider_user_id','provider','profile_type_id'];

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function profileType()
    {
        return $this->belongsTo('\App\ProfileType');
    }
}
