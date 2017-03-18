<?php

namespace App\Profile;

use App\Scopes\Profile as ScopeProfile;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use ScopeProfile;

    protected $table = 'profile_books';

    protected $fillable = ['id','title','description','publisher','release_date','url','isbn'];

    protected $visible = ['id','title','description','publisher','release_date','url','isbn','total'];
    
    protected $appends = ['total'];

    public function setReleaseDateAttribute($value)
    {
        $this->attributes['release_date'] = date('Y-m-d',strtotime($value));
    }

    public function getReleaseDateAttribute($value)
    {
        if(!$value){
            return;
        }
        return date("d-m-Y",strtotime($value));
    }
    
    public function getTotalAttribute()
    {
        return $this->where('profile_id',$this->profile_id)->count();
    }
}
