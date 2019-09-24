<?php

namespace App\V2;

use App\Privacy;
use App\Traits\HasPreviewContent;
use App\Traits\IdentifiesOwner;
use App\Polling as BasePolling;

class Polling extends BasePolling
{
	use IdentifiesOwner, HasPreviewContent;

	protected $visible = ['id','title','profile_id','company_id','created_at',
        'deleted_at','updated_at','is_expired','expired_time','options','poll_meta'];

    protected $appends = ['options','owner','poll_meta'];

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