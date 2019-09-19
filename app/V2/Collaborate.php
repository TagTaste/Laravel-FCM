<?php

namespace App\V2;

use App\Privacy;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use App\Traits\IdentifiesOwner;
use App\Collaborate as BaseCollaborate;
use Illuminate\Support\Facades\Redis;

class Collaborate extends BaseCollaborate
{
	use IdentifiesOwner, GetTags, HasPreviewContent;

	protected $visible = ["id", "title", "description", "profile_id", "company_id", "has_tags", "collaborate_type", "expires_on", "updated_at", "created_at", "deleted_at"];

	public function getOwnerAttribute()
    {
    	$data = array();
    	$owner = $this->owner();
    	if (!is_null($this->profile_id)) {
    		$keyRequired = [
	            'id',
	            'user_id',
	            'name',
	            'designation',
	            'handle',
	            'tagline',
	            'image_meta',
	            'isFollowing'
	        ];
	        $data = array_intersect_key(
	            $owner->toArray(), 
	            array_flip($keyRequired)
	        );
	        
	        foreach ($data as $key => $value) {
	            if (is_null($value) || $value == '')
	                unset($data[$key]);
	        }
	        return $data;
    	} else {
    		if (!is_null($this->company_id)) {
    			$data = [
		            'id' => $owner->id,
		            'profile_id' => $owner->profileId,
		            'name' => $owner->name,
		            'logo_meta' => $owner->logo_meta
		        ];
		        return $data;
    		}
    	}
    	return $data;
    }
}