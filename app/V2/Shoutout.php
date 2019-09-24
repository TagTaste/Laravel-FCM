<?php

namespace App\V2;

use App\Privacy;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use App\Traits\IdentifiesOwner;
use App\Shoutout as BaseShoutout;
use Illuminate\Support\Facades\Redis;

class Shoutout extends BaseShoutout
{
	use IdentifiesOwner, GetTags, HasPreviewContent;

	protected $visible = ['id','content','profile_id','company_id','has_tags','created_at','updated_at', 'preview', 'cloudfront_media_url', 'media_url', 'media_json', 'mediaJson'];

	public function getOwnerAttribute()
    {
    	$data = array();
    	$owner = $this->owner();
    	if (!is_null($this->company_id)) {
    		$data = [
	            'id' => $owner->id,
	            'profile_id' => $owner->profileId,
	            'name' => $owner->name,
	            'logo_meta' => $owner->logo_meta
	        ];
	        return $data;
    	} else {
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
    		}
    	}
    	return $data;
    }
}