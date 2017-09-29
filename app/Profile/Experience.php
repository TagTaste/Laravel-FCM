<?php

namespace App\Profile;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use Profile;

    protected $fillable = ['company','designation','description','location',
    'start_date','end_date','current_company','profile_id'];

    protected $visible = ['id','company','designation','description','location',
        'start_date','end_date','current_company'];

    protected $touches = ['profile'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('experiences', function (Builder $builder) {
            $builder->orderBy('current_company','desc')->orderBy('start_date', 'desc');
        });
        
        self::created(function($model){
           \App\Documents\Profile::create($model->profile);
        });
        
        self::updated(function($model){
           \App\Documents\Profile::create($model->profile);
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
    
    public function getStartDateAttribute($value)
    {
        if (!empty($value)) {
            return date("m-Y", strtotime($value));
        }
    }
    
    public function getEndDateAttribute($value)
    {
        if (!empty($value)) {
            return date("m-Y", strtotime($value));
        }
    }
}
