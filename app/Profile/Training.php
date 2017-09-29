<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Training extends Model
{
    protected $table = 'trainings';
    protected $fillable = ['title','trained_from','completed_on','profile_id'];

    protected $visible = ['id','title','trained_from','completed_on','profile_id'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('profile_books', function (Builder $builder) {
            $builder->orderBy('release_date', 'desc');
        });
    }
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

}
