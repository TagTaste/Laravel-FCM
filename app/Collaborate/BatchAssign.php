<?php

namespace App\Collaborate;

use App\Recipe\Collaborate;
use Illuminate\Database\Eloquent\Model;


class BatchAssign extends Model {

    protected $table = 'collaborate_batches_assign';

    protected $fillable = ['batch_id','profile_id','begin_tasting','collaborate_id'];

    protected $visible = ['batch_id','profile_id','begin_tasting','batches','collaborate_id'];

    protected $appends = ['current_status'];

    protected $with = ['batches'];

    public function batches()
    {
        return $this->belongsTo(Batches::class,'batch_id','id');
    }

    public function getCurrentStatusAttribute()
    {
        $currentStatus =  \DB::table('collaborate_tasting_user_review')->select('current_status')
            ->where('batch_id',$this->batch_id)->where('profile_id',request()->user()->profile->id)->first();
        return isset($currentStatus) ? $currentStatus->current_status : 0;
    }

}
