<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
//    use StartEndDate;

    protected $table = 'education';

    protected $fillable = ['degree','college','field','grade','percentage','description','start_date','end_date','ongoing','location','profile_id'];

    protected $visible = ['id','degree','college','field','grade','percentage','description','start_date','end_date','ongoing','location','profile_id'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('education', function (Builder $builder) {
            $builder->orderBy('ongoing','desc')->orderBy('start_date', 'desc');
        });
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
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
