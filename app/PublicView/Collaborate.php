<?php

namespace App\PublicView;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Collaborate as BaseCollaborate;
use Illuminate\Support\Facades\Redis;

class Collaborate extends BaseCollaborate
{
    use IdentifiesOwner, SoftDeletes;

    protected $visible = ['id','title', 'i_am', 'looking_for',
        'expires_on','video','location','categories',
        'description','project_commences','images',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id','notify','owner'
        ,'privacy_id','created_at','deleted_at', 'file1','deliverables','start_in','state','updated_at','profile','images_meta','videos_meta','addresses','collaborate_allergens','brand_name','brand_logo','description_updated', 'is_taster_residence'];

    protected $appends = ['owner' ,'images', 'description_updated'];

    protected $with = ['profile','addresses','collaborate_allergens'];

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
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        return $meta;
    }


    public function getImagesAttribute ($value)
    {
        $imageArray = [];
        if(isset($value))
        {
            if(!is_array($value))
            {
                $images = json_decode($value, true);
                $i = 1;
                foreach ($images as $image) {
                    $imageArray[] = isset($image['image'.$i]) ? $image['image'.$i] : $image;
                    $i++;
                }
            }
            else
                return $value;
        }
        return $imageArray;
    }

    public function getImagesMetaAttribute($value)
    {
        if(isset($value))
            return json_decode($value,true);
        return [];
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


    public function addresses()
    {
        return $this->hasMany('App\Collaborate\Addresses');
    }
    public function collaborate_allergens()
    {
        return $this->hasMany('App\Collaborate\Allergens');
    }

    /**
     * Compute the description with old fields.
     *
     * @return string
     */
    public function getDescriptionUpdatedAttribute()
    {
        $data = "";

        if (!is_null($this->description) && 
            (is_string($this->description) && strlen($this->description))) {
            $data = $data.$this->description;
        }

        if (!is_null($this->start_in) && 
            (is_string($this->start_in) && strlen($this->start_in))) {
            $data = $data."\n\n"."Starts In\n".$this->start_in;
        }

        if (!is_null($this->duration) && 
            (is_string($this->duration) && strlen($this->duration))) {
            $data = $data."\n\n"."Duration\n".$this->duration;
        }

        if (!is_null($this->eligibility_criteria) && 
            (is_string($this->eligibility_criteria) && strlen($this->eligibility_criteria))) {
            $data = $data."\n\n"."Eligibility Criteria\n".$this->eligibility_criteria;

            if (!is_null($this->collaborate_occupations) && count($this->collaborate_occupations)) {
                $data = $data."\n\n"."Consumers with Profiles";
                $collaborate_occupations = $this->collaborate_occupations->toArray();
                if (count($collaborate_occupations)) {
                    foreach ($collaborate_occupations as $key => $collaborate_occupation) {
                        if (is_string($collaborate_occupation['name']) && strlen($collaborate_occupation['name'])) {
                            $data = $data."\n".$collaborate_occupation['name'];
                        }
                    }    
                }
            }

            if (!is_null($this->collaborate_specializations) && count($this->collaborate_specializations)) {
                $data = $data."\n\n"."Experts with Specializations";
                $collaborate_specializations = $this->collaborate_specializations->toArray();
                if (count($collaborate_specializations)) {
                    foreach ($collaborate_specializations as $key => $collaborate_specialization) {
                       if (is_string($collaborate_specialization['name']) && strlen($collaborate_specialization['name'])) {
                            $data = $data."\n".$collaborate_specialization['name'];
                        }
                    }    
                }
            }
        }

        if (!is_null($this->project_commences) && 
            (is_string($this->project_commences) && strlen($this->project_commences))) {
            $data = $data."\n\n"."Deliverables\n".$this->project_commences;
        }

        if (!is_null($this->financials) && 
            (is_string($this->financials) && strlen($this->financials))) {
            $data = $data."\n\n"."Financials\n".$this->financials;
        }

        if (!is_null($this->occassion) && 
            (is_string($this->occassion) && strlen($this->occassion))) {
            $data = $data."\n\n"."Event\n".$this->occassion;
        }
        return $data;
    }
}
