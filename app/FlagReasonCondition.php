<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlagReasonCondition extends Model
{
    // conditons belongs to one reason
    public function flagReason()
    {
        return $this->belongsTo(FlagReason::class, 'flag_reason_id', 'id');
    }
}
