<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlagReason extends Model
{
    protected $visible = ['id', 'reason', 'slug'];

    // one reason to multiple conditions
    public function conditions()
    {
        return $this->hasMany(FlagReasonCondition::class, 'flag_reason_id', 'id');
    }

     // one reason to multiple flagged model reasons
     public function modelFlagReasons()
     {
        return $this->hasMany(ModelFlagReason::class, 'flag_reason_id', 'id');
     }
}
