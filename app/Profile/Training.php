<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = 'trainings';
    protected $fillable = ['title','trained_from','completed_on','profile_id'];

    protected $visible = ['id','title','trained_from','completed_on','profile_id'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('trainings', function (Builder $builder) {
            $builder->orderBy('completed_on', 'desc');
        });
    }
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
    
    public function getCompletedOnAttribute($value)
    {
        if (!empty($value)) {
            return date("m-Y", strtotime($value));
        }
    }

}
