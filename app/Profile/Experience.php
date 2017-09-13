<?php

namespace App\Profile;

use App\Scopes\Profile;
use App\Traits\StartEndDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Experience extends Model
{
    use StartEndDate,Profile;

    protected $fillable = ['company','designation','description','location',
    'start_date','end_date','current_company','profile_id'];

    protected $visible = ['id','company','designation','description','location',
        'start_date','end_date','current_company'];


    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('experiences', function (Builder $builder) {
            $builder->orderBy('start_date', 'desc');
        });
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function setCurrentCompanyAttribute($value){
        $this->attributes['current_company'] = empty($value) ? 0 : 1;
    }

    public function getCurrentCompanyAttribute($value){
      if(is_null($value)){
        return false;
      }
      return $value;
    }
}
