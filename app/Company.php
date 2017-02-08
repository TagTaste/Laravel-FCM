<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function albums()
    {
        return $this->belongsToMany('App\Album','profile_albums','profile_id','album_id');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Company\Award','company_awards','company_id','award_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
