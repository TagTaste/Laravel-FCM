<?php

namespace App\V2\Detailed;

use App\Privacy;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use App\Traits\IdentifiesOwner;
use App\Collaborate as BaseCollaborate;
use Illuminate\Support\Facades\Redis;

class Collaborate extends BaseCollaborate
{
	use IdentifiesOwner, GetTags, HasPreviewContent;

	protected $visible = ['id','title','expires_on','profile_id','company_id','created_at','updated_at','deleted_at','video','location','privacy_id','description','duration','file1','state','step','type','type_id','is_taster_residence','collaborate_type','brand_name','brand_logo','methodology_id','no_of_batches','global_question_id','images_meta','applicationCount','type','product_review_meta','tasting_methodology','profile','company','categories','addresses','collaborate_specializations','collaborate_allergens','description_updated'];

    protected $appends = ['description_updated', 'applicationCount', 'product_review_meta', 'type','tasting_methodology'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\V2\Profile::class);
    }
    
    /**
     * Which company created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\V2\Company::class);
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

    public function getApplicationCountAttribute()
    {
        $interestedCount = 0;
        if($this->collaborate_type != 'product-review')
        {
            return (int)Redis::hGet("meta:collaborate:" . $this->id,"applicationCount") ?? 0;
        } else {
            return \DB::table('collaborate_applicants')->where('collaborate_id',$this->id)->distinct()->get(['profile_id'])->count();
        }
        return $this->interestedCount;
    }
}