<?php

namespace App\Collaborate;

use App\Recipe\Collaborate;
use Illuminate\Database\Eloquent\Model;


class Batches extends Model {

    protected $table = 'collaborate_batches';

    protected $fillable = ['name','notes','allergens','instruction','color_id','collaborate_id','created_at','updated_at','deleted_at'];

    protected $visible = ['id','name','notes','allergens','instruction','color_id','collaborate_id',
        'color','created_at','updated_at','current_status','deleted_at'];

    protected $appends = ['color'];

//    protected $with = ['color'];

    public static function boot()
    {
        self::created(function($model){
            $model->addToCache();
            });

        self::updated(function($model){
            $model->addToCache();
        });
        self::deleted(function($model){
            $model->removeFromCache();
        });
    }

    public function addToCache()
    {
        $data = ['id'=>$this->id,'name'=>$this->name,'notes'=>$this->notes,'allergens'=>$this->allergens,'instruction'=>$this->instruction,
            'color_id'=>$this->color_id,'collaborate_id'=>$this->collaborate_id,'color'=>$this->color,
            'created_at'=>isset($this->created_at) ? $this->created_at->toDateTimeString() : null ,'updated_at'=>isset($this->updated_at) ? $this->updated_at->toDateTimeString() : null];
        \Redis::set("batch:" . $this->id,json_encode($data));

    }

    public function removeFromCache()
    {
        \Redis::del("batch:" . $this->id);
    }

    public function getColorAttribute()
    {
        return \DB::table('collaborate_batches_color')->where('id',$this->color_id)->first();
    }



    public function getCurrentStatusAttribute()
    {
        $currentStatus =  \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$this->collaborate_id)
            ->where('batch_id',$this->id)->where('profile_id',request()->user()->profile->id)->orderBy('id', 'desc')->first();

        if(isset($currentStatus))
        {
            return $currentStatus->current_status;
        }
        else
        {
            $batchAssign = \DB::table('collaborate_batches_assign')->where('batch_id',$this->id)->where('profile_id',request()->user()->profile->id)->first();
            return isset($batchAssign) ? $batchAssign->begin_tasting : 0;
        }

    }

}
