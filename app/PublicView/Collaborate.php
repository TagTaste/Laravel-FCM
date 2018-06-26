<?php

namespace App\PublicView;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Collaborate as BaseCollaborate;

class Collaborate extends BaseCollaborate
{
    use IdentifiesOwner, SoftDeletes;

    protected $visible = ['id','title', 'i_am', 'looking_for',
        'expires_on','video','location','categories',
        'description','project_commences','images',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id','notify','owner'
        ,'privacy_id','created_at','deleted_at', 'file1','deliverables','start_in','state','updated_at','profile'];


    protected $appends = ['owner'];

    protected $with = ['profile'];

    public function company()
    {
        return $this->belongsTo(\App\PublicView\Company::class);
    }

    public function profile()
    {
        return $this->belongsTo(\App\PublicView\Profile::class);
    }

    public function getOwnerAttribute()
    {
        return $this->owner();
    }

    public function getMetaForPublic()
    {
        $meta = [];
        $key = "meta:collaborate:likes:" . $this->id;
        $meta['likeCount'] = \Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        return $meta;
    }


    public function getImagesAttribute ($value)
    {
        if(isset($value)) {
            $images = json_decode($value, true);
            $imageArray = [];
            $i = 1;
            foreach ($images as $image) {
                $imageArray[] = $image['image' . $i];
                $i++;
            }
            return $imageArray;
        }
    }

    public function getStateAttribute($value)
    {
        if($value == 1)
            return 'Active';
        else if($value == 3)
            return 'Expired';
        else
            return 'Delete';
    }

}
