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

	protected $visible = ['id','title','expires_on','profile_id','company_id','created_at','updated_at','deleted_at','video','location','privacy_id','description','duration','file1','state','step','type_id','is_taster_residence','collaborate_type','brand_name','brand_logo','methodology_id','no_of_batches','global_question_id','images_meta','applicationCount','type','product_review_meta','tasting_methodology','profile','company','categories','addresses','collaborate_specializations','collaborate_allergens'];

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
     * Which company created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getDescriptionAttribute($value)
    {
        $data = $value;
        // dd($data);
        if (!is_null($this->start_in)) {
            $data = $data."\n\n"."Starts In:\n".$this->start_in;
        }

        if (!is_null($this->duration)) {
            $data = $data."\n\n"."Duration:\n".$this->duration;
        }

        if (!is_null($this->eligibility_criteria)) {
            $data = $data."\n\n"."Eligibility Criteria:\n".$this->eligibility_criteria;

            if (!is_null($this->collaborate_occupations) && count($this->collaborate_occupations)) {
                $data = $data."\n\n"."Consumers with Profiles:";
                $collaborate_occupations = $this->collaborate_occupations->toArray();
                if (count($collaborate_occupations)) {
                    foreach ($collaborate_occupations as $key => $collaborate_occupation) {
                        $data = $data."\n".$collaborate_occupation['name'];
                    }    
                }
            }

            if (!is_null($this->collaborate_specializations) && count($this->collaborate_specializations)) {
                $data = $data."\n\n"."Consumers with Profiles:";
                $collaborate_specializations = $this->collaborate_specializations->toArray();
                if (count($collaborate_specializations)) {
                    foreach ($collaborate_specializations as $key => $collaborate_specialization) {
                        $data = $data."\n".$collaborate_specialization['name'];
                    }    
                }
            }
        }

        if (!is_null($this->project_commences)) {
            $data = $data."\n\n"."Deliverables:\n".$this->project_commences;
        }

        if (!is_null($this->financials)) {
            $data = $data."\n\n"."Financials:\n".$this->financials;
        }

        if (!is_null($this->occassion)) {
            $data = $data."\n\n"."Event:\n".$this->occassion;
        }
        // dd($data);
        return $data;
    }

}