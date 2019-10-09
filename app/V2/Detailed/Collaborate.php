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
}