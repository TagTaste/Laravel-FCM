<?php

namespace App\Profile;

use App\Scopes\Profile as ScopeProfile;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PositionInCollection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Patent extends Model
{
    use ScopeProfile, SoftDeletes;

    protected $table = 'profile_patents';

    protected $fillable = ['id','title','description','publish_date','patent_number','url','profile_id'];

    protected $visible = ['id','title','description','publish_date','patent_number','url','profile_id'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('profile_patents', function (Builder $builder) {
            $builder->orderBy('publish_date', 'desc');
        });
    }
    
    public function getPublishDateAttribute($value)
    {
        if (!empty($value)) {
            return date("m-Y", strtotime($value));
        }
    }

}
