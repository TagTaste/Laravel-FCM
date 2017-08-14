<?php

namespace App\Profile;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use Profile;

    protected $table = 'profile_shows';
    protected $fillable = ['id','title','description','channel','date','url','appeared_as'];
    protected $visible = ['id','title','description','channel',
        'date','url','appeared_as','total'];
    
    protected $appends = ['total'];
    
    public function getTotalAttribute()
    {
        return $this->where('profile_id',$this->profile_id)->count();
    }


    public function setDateAttribute($value)
    {
        if(!empty($value)) {
            $value = "01-" . $value;
            $this->attributes['end_date'] = date('Y-m-d', strtotime($value));
        }
    }

    public function getDateAttribute($value)
    {
        return date("m-Y",strtotime($value));
    }
}
