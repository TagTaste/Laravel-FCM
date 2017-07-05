<?php

namespace App;

use App\Traits\StartEndDate;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use StartEndDate;

    protected $table = 'education';

    protected $fillable = ['degree','college','field','grade','percentage','description','start_date','end_date','ongoing'];

    protected $visible = ['degree','college','field','grade','percentage','description','start_date','end_date','ongoing'];


    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
