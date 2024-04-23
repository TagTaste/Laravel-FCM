<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelFlagReason extends Model
{
    protected $fillable = ['model_id', 'flag_reason_id', 'reason', 'slug', 'profile_id', 'company_id', 'model','created_at', 'updated_at'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // flagged model reasons belongs to one reason
    public function flagReason()
    {
        return $this->belongsTo(FlagReason::class, 'flag_reason_id', 'id');
    }
}
