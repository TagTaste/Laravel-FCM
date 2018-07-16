<?php

namespace App\Collaborate;

use App\Recipe\Collaborate;
use Illuminate\Database\Eloquent\Model;


class Batches extends Model {

    protected $table = 'collaborate_batches';

    protected $fillable = ['name','notes','allergens','instruction','color_id','collaborate_id'];

    protected $visible = ['id','name','notes','allergens','instruction','color_id','collaborate_id','collaborate','current_status','collaborate_title'];

    protected $appends = ['current_status','collaborate_title'];


    public function getCollaborateTitleAttribute()
    {
        $collaborate = \DB::table('collaborates')->where('id',$this->collaborate_id)->first();

        return $collaborate->title;
    }

    public function getCurrentStatusAttribute()
    {
        $currentStatus =  \DB::table('collaborate_tasting_user_review')->select('current_status')->where('collaborate_id',$this->collaborate_id)->where('batch_id',$this->id)
                ->where('profile_id',request()->user()->profile->id)->first();
        return isset($currentStatus) ? $currentStatus->current_status : 0;
    }

}
