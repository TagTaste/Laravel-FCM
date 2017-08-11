<?php

namespace App\Profile;

use App\Scopes\Profile as ScopeProfile;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PositionInCollection;
use App\Traits\StartEndDate;

class Book extends Model
{
    use ScopeProfile, PositionInCollection;

    protected $table = 'profile_books';

    protected $fillable = ['id','title','description','publisher','release_date','url','isbn'];

    protected $visible = ['id','title','description','publisher','release_date','url','isbn','total'];
    
    protected $appends = ['total'];

    public function setReleaseDateAttribute($value)
    {
        if(!empty($value)){
            $value = $value . '-01';
            $this->attributes['release_date'] = date('Y-m-d',strtotime($value));
        }
    }

    public function getReleaseDateAttribute($value)
    {
        if(!empty($value)){
            return date("m-Y",strtotime($value));
        }
    }
    
    /**
     * Should have been named Count
     *
     * @return mixed
     */
    public function getTotalAttribute()
    {
        $books = $this->select('id')->where('profile_id',$this->profile_id)->orderBy('created_at','asc')->get();
        return $this->getCount($books);
    }
}
