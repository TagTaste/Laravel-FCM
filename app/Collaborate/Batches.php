<?php

namespace App\Collaborate;

use App\Recipe\Collaborate;
use Illuminate\Database\Eloquent\Model;


class Batches extends Model {

    protected $table = 'collaborate_batches';

    protected $fillable = ['name','notes','allergens','instruction','color_id','collaborate_id','created_at','updated_at'];

    protected $visible = ['id','name','notes','allergens','instruction','color_id','collaborate_id','collaborate',
        'color','assignedCount','reviewedCount','created_at','updated_at'];

    protected $appends = ['color','reviewedCount','assignedCount'];

//    protected $with = ['color'];

    public function getColorAttribute()
    {
        return \DB::table('collaborate_batches_color')->where('id',$this->color_id)->first();
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
