<?php

namespace App\Profile;

use App\Traits\StartEndDate;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use StartEndDate;

    protected $fillable = ['company','designation','description','location',
    'start_date','end_date','current_company','profile_id'];

    protected $visible = ['company','designation','description','location',
        'start_date','end_date','current_company'];

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }


}
