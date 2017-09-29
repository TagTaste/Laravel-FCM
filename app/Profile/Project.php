<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    protected $fillable = ['title','description','completed_on','url','profile_id'];

    protected $visible = ['id','title','description','completed_on','url','profile_id'];

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

    public function members()
    {
        return $this->hasMany('App\ProjectMember');
    }

}
