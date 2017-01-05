<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ideabook extends Model
{
    protected $fillable = ['name','description','privacy_id','user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function privacy()
    {
        return $this->belongsTo('App\Privacy');
    }
}
