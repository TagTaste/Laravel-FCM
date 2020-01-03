<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;
use App\Traits\HasPreviewContent;
use App\Traits\GetTags;

class ReviewCollection extends Model
{
	use IdentifiesOwner, SoftDeletes, GetTags, HasPreviewContent;

    protected $table='review_collections';

    protected $fillable = ['title','subtitle','description','image','type','category_type','is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','title','subtitle','description','backend','category_type','images_meta','elements'];

    protected $with = ['elements'];

    protected $appends = ['images_meta','backend'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function elements()
    {
        return $this->hasMany(\App\ReviewCollectionElement::class, 'collection_id', 'id')->inRandomOrder();
    }

    public function getImagesMetaAttribute()
    {
        if (!is_null($this->image)) {
            return json_decode($this->image);
        }
        return $this->image;
    }

    public function getBackendAttribute()
    {
        return $this->type;
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $content = $this->getContent($this->description);
        $data['title'] = $this->title;
        $data['description'] = substr($content,0,150);
        $data['ogTitle'] = $this->title;
        $data['ogDescription'] = substr($content,0,150);
        $data['ogImage'] = null;
        if (!is_null($this->images_meta) && isset($this->images_meta[0])) {
            $data['ogImage'] = $this->images_meta[0]->original_photo; 
        }
        $data['cardType'] = 'summary_large_image';
        $data['ogUrl'] = env('APP_URL').'/collection/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/collection/'.$this->id;

        return $data;

    }

}
