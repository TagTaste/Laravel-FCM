<?php

namespace App;

use App\Traits\StartEndDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
}
