<?php

namespace App\Profile;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use Profile;

    protected $table = 'profile_shows';
    protected $fillable = ['id','title','description','channel','date','url','appeared_as','profile_id'];
    protected $visible = ['id','title','description','channel',
        'date','url','appeared_as','total'];
    
    protected $appends = ['total'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('profile_shows', function (Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
    }
    
    public function getTotalAttribute()
    {
        return $this->where('profile_id',$this->profile_id)->count();
    }
    
    public function getDateAttribute($value)
    {
        if (!empty($value)) {
            return date("m-Y", strtotime($value));
        }
    }

}
