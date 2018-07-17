<?php

namespace App\Collaborate;

use App\Recipe\Collaborate;
use Illuminate\Database\Eloquent\Model;


class Batches extends Model {

    protected $table = 'collaborate_batches';

    protected $fillable = ['name','notes','allergens','instruction','color_id','collaborate_id'];

    protected $visible = ['id','name','notes','allergens','instruction','color_id','collaborate_id','collaborate',
        'current_status','collaborate_title','color','assignedCount','reviewedCount'];

    protected $appends = ['current_status','collaborate_title','color','reviewedCount','assignedCount'];

//    protected $with = ['color'];

    public function getColorAttribute()
    {
        return \DB::table('collaborate_batches_color')->where('id',$this->color_id)->first();
    }

    public function getCollaborateTitleAttribute()
    {
        $collaborate = \DB::table('collaborates')->where('id',$this->collaborate_id)->first();

        return $collaborate->title;
    }

    public function getCurrentStatusAttribute()
    {
        $currentStatus =  \DB::table('collaborate_tasting_user_review')->select('current_status')->where('collaborate_id',$this->collaborate_id)
            ->where('batch_id',$this->id)->where('profile_id',request()->user()->profile->id)->first();
        return isset($currentStatus) ? $currentStatus->current_status : 0;
    }

    public function getReviewedCountAttribute()
    {
        return \DB::table('collaborate_tasting_user_review')->where('current_status',2)->where('collaborate_id',$this->collaborate_id)
            ->where('batch_id',$this->id)->distinct('profile_id')->count();
    }

    public function getAssignedCountAttribute()
    {
        return \DB::table('collaborate_batches_assign')->where('batch_id',$this->id)->distinct('profile_id')->count();
    }

}
