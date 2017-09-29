<?php

namespace App\Profile;

use App\Scopes\Profile as ScopeProfile;
use App\Traits\PositionInCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use ScopeProfile, PositionInCollection;

    protected $table = 'profile_books';

    protected $fillable = ['id','title','description','publisher','release_date','url','isbn','profile_id'];

    protected $visible = ['id','title','description','publisher','release_date','url','isbn','total'];
    
    protected $appends = ['total'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('profile_books', function (Builder $builder) {
            $builder->orderBy('release_date', 'desc');
        });
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
    
    public function getReleaseDateAttribute($value)
    {
        if (!empty($value)) {
            return date("m-Y", strtotime($value));
        }
    }
}
