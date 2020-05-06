<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class FeedCard extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'feed_card';

    protected $fillable = ['data_type','data_id','title','subtitle','name','image','description','icon','is_active','profile', 'company','created_at','updated_at','deleted_at'];

    protected $visible = ['id','title','subtitle','name','image_meta','description','icon','profile', 'company'];

    protected $appends = ['profile', 'company', 'image_meta'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getProfileAttribute()
    {
        if ($this->data_type == "profile") {
            return \App\V2\Profile::where('id',$this->data_id)->first();
        }
        return null;
        
    }

    /**
     * Which company created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getCompanyAttribute()
    {
        if ($this->data_type == "company") {
            return \App\V2\Company::where('id',$this->data_id)->first();
        }
        return null;
    }

    /**
     * Which company created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getImageMetaAttribute()
    {   
        if (is_null($this->image) && !is_null(json_decode($this->image))) {
            return json_decode($this->image);
        } else {
            if ($this->data_type == "profile" && isset($this->profile->image_meta) && !is_null($this->profile->image_meta) && !is_null(json_decode($this->profile->image_meta))) {
                return json_decode($this->profile->image_meta);
            } else if ($this->data_type == "company" && isset($this->company->logo_meta) && !is_null($this->company->logo_meta) && !is_null(json_decode($this->company->logo_meta))) {
                return json_decode($this->company->logo_meta);
            } else {
                return (object)array();
            } 
        }
    }


    /**
     * @param int $profileId
     * @return array
     */
    public function getMetaFor()
    {
        $meta = [];
        $meta['type'] = $this->data_type;
        return $meta;
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['owner'] = $this->id;
        $data['title'] = 'Check out '.$this->title.' on TagTaste';
        $data['description'] = substr($this->description,0,155);
        $data['ogTitle'] = 'Check out '.$this->title.' on TagTaste';
        $data['ogDescription'] = substr($this->description,0,155);
        $data['ogImage'] = $this->image_meta->original_photo;
        $data['cardType'] = 'summary_large_image';
        $data['ogUrl'] = env('APP_URL').'/feed/card/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/feed/card/'.$this->id;
        if(empty($this->image_meta->original_photo)) {
            $data['cardType'] = 'summary';
        }
        return $data;
    }
}
