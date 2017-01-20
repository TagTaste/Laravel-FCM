<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['company','designation','description','location',
    'start_date','end_date','current_company','profile_id'];

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function set()
    {
        
    }
}
