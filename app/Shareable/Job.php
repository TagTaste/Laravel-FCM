<?php

namespace App\Shareable;

use App\Shareable\Share;


class Job extends Share
{
    protected $with = ['job'];

    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }

    public function job()
    {
        return $this->belongsTo(\App\Job::class,'job_id');
    }

    public function getMetaFor($profileId){
        $meta = [];
        $meta = (object) $meta;
        return $meta;
    }

    public function getMetaForPublic(){
        $meta = [];
        $meta = (object) $meta;
        return $meta;
    }
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->job->job_id,
            'share_id' => $this->id,
            'content' => null != $this->content ? $this->content : $this->job->title,
            'image' => null,
            'shared' => true
        ];
    }
}
