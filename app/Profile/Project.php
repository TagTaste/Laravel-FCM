<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title','description','completed_on','url','profile_id'];

    protected $visible = ['id','title','description','completed_on','url','profile_id'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('projects', function (Builder $builder) {
            $builder->orderBy('completed_on', 'desc');
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
