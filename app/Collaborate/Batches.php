<?php

namespace App\Collaborate;

use App\Recipe\Collaborate;
use Illuminate\Database\Eloquent\Model;


class Batches extends Model {

    protected $table = 'collaborate_batches';

    protected $fillable = ['name','notes','allergens','instruction','color_id','collaborate_id','created_at','updated_at'];

    protected $visible = ['id','name','notes','allergens','instruction','color_id','collaborate_id','collaborate',
        'color','created_at','updated_at','current_status'];

    protected $appends = ['color','current_status'];

//    protected $with = ['color'];

    public function getColorAttribute()
    {
        return \DB::table('collaborate_batches_color')->where('id',$this->color_id)->first();
    }



    public function getCurrentStatusAttribute()
    {
        $currentStatus =  \DB::table('collaborate_tasting_user_review')->select('current_status')->where('collaborate_id',$this->collaborate_id)
            ->where('batch_id',$this->id)->where('profile_id',request()->user()->profile->id)->first();
        return isset($currentStatus) ? $currentStatus->current_status : 0;
    }

}
