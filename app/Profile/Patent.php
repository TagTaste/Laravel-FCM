<?php

namespace App\Profile;

use App\Scopes\Profile as ScopeProfile;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PositionInCollection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patent extends Model
{
    use ScopeProfile, SoftDeletes;

    protected $table = 'profile_patents';

    protected $fillable = ['id','title','description','publish_date','patent_number','url','profile_id'];

    protected $visible = ['id','title','description','publish_date','patent_number','url','profile_id'];
    
    public function setPublishDateAttribute($value)
    {
        if(!empty($value)){
            $this->attributes['publish_date'] = date('Y-m-d',strtotime($value));
        }
    }

    public function getPublishDateAttribute($value)
    {
        if(!empty($value)){
            return date("d-m-Y",strtotime($value));
        }
    }

}
