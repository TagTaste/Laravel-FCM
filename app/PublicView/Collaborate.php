<?php

namespace App\PublicView;

use App\Channel\Payload;
use App\Collaboration\Collaborator;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collaborate extends Model
{
    use IdentifiesOwner, SoftDeletes;

    protected $visible = ['id','title', 'i_am', 'looking_for',
        'expires_on','video','location','categories',
        'description','project_commences','images',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id','notify','privacy_id','created_at','deleted_at',
        'file1','deliverables','start_in','state','updated_at'];


    protected $appends = ['owner'];

    public function company()
    {
        return $this->belongsTo(\App\PublicView\Company::class);
    }

    public function profile()
    {
        return $this->belongsTo(\App\PublicView\Profile::class);
    }

    public function getMetaFor()
    {
        $meta = [];
        $key = "meta:photo:likes:" . $this->id;
        $meta['likeCount'] = \Redis::sCard($key);
        $meta['commentCount'] = \DB::table('comments_collaborates')->where('collaborate_id')->count();
        return $meta;
    }


    public function getImagesAttribute ()
    {

        $images=[];
        if($this->company_id){
            for($i=1;$i<=5;$i++)
            {
                if($this->{"image".$i}!==null)
                {
                    $images[] = !is_null($this->{"image".$i}) ? \Storage::url($this->{"image".$i}) : null;

                }
            }
        }
        else
        {
            for($i=1;$i<=5;$i++)
            {
                if($this->{"image".$i}!==null)
                {
                    $images[] = !is_null($this->{"image".$i}) ? \Storage::url($this->{"image".$i}) : null;
                }
            }
        }

        return $images;
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
